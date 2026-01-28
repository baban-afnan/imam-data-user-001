<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\AgentService;
use App\Models\Service;
use App\Models\ServiceField;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class NinValidationController extends Controller
{
    public function index(Request $request)
    {
        $validationService = Service::where('name', 'Validation')->first();
        $ipeService = Service::where('name', 'IPE')->first();

        // Fetch fields for both services
        $validationFields = $validationService ? $validationService->fields : collect();
        $ipeFields = $ipeService ? $ipeService->fields : collect();

        // Combine fields for the dropdown, distinguishing them
        $services = collect();
        $user = Auth::user();
        $role = $user->role ?? 'user';
        
        foreach ($validationFields as $field) {
            $price = $field->prices()->where('user_type', $role)->value('price') ?? $field->base_price;
            $services->push([
                'id' => $field->id,
                'name' => $field->field_name,
                'price' => $price,
                'type' => 'validation',
                'service_id' => $field->service_id
            ]);
        }
        
        foreach ($ipeFields as $field) {
            $price = $field->prices()->where('user_type', $role)->value('price') ?? $field->base_price;
            $services->push([
                'id' => $field->id,
                'name' => $field->field_name,
                'price' => $price,
                'type' => 'ipe',
                'service_id' => $field->service_id
            ]);
        }

        $wallet = Wallet::where('user_id', Auth::id())->first();
        
        $query = AgentService::where('user_id', Auth::id())
            ->whereIn('service_type', ['nin_validation', 'ipe']);

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nin', 'like', "%{$searchTerm}%")
                  ->orWhere('tracking_id', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $submissions = $query->orderByRaw("
          CASE status 
        WHEN 'pending' THEN 1 
        WHEN 'processing' THEN 2 
        WHEN 'successful' THEN 3 
        WHEN 'failed' THEN 4 
        WHEN 'resolved' THEN 5 
        WHEN 'rejected' THEN 6 
        ELSE 7 
            END
        ")->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('nin.validation', compact('services', 'wallet', 'submissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_field' => 'required',
            'nin' => 'required_if:service_type,validation|nullable|digits:11',
            'tracking_id' => 'required_if:service_type,ipe|nullable|string|min:15',
        ]);

        // Determine service type based on selected field
        $fieldId = $request->service_field;
        $serviceField = ServiceField::with('service')->findOrFail($fieldId);
        
        // Infer service type from the service the field belongs to
        $serviceType = $serviceField->service->name == 'Validation' ? 'validation' : 'ipe';

        $user = Auth::user();
        $role = $user->role ?? 'user';
        
        // Calculate price based on role
        $servicePrice = $serviceField->prices()->where('user_type', $role)->value('price') ?? $serviceField->base_price;

        $wallet = Wallet::where('user_id', $user->id)->first();

        if (!$wallet || $wallet->balance < $servicePrice) {
            return back()->with('error', 'Insufficient wallet balance.');
        }

        // Call API First (Do not charge if this fails)
        $apiKey = env('AREWA_API_TOKEN');
        $apiBaseUrl = env('AREWA_BASE_URL');
        $apiUrl = rtrim($apiBaseUrl, '/') . '/nin/validation';

        $payload = [
            'description' => $request->description ?? "My Reference",
        ];

        if ($serviceType == 'validation') {
            $payload['nin'] = $request->nin;
            $payload['field_code'] = '015'; // Code for Validation
        } else {
            $payload['tracking_id'] = $request->tracking_id;
            $payload['field_code'] = '002'; // Code for IPE
        }

        try {
            $response = Http::withToken($apiKey)
                ->acceptJson()
                ->post($apiUrl, $payload);
            
            $data = $response->json();

            if (!$response->successful() || (isset($data['status']) && $data['status'] == 'error')) {
                return back()->with('error', 'API Submission Failed: ' . ($data['message'] ?? 'Unknown Error'));
            }
        } catch (\Exception $e) {
            Log::error('API Error: ' . $e->getMessage());
            return back()->with('error', 'Connection Error: Unable to reach service provider.');
        }

        // API Success - Proceed to Charge and Record
        DB::beginTransaction();

        try {
            // Deduct from wallet
            $wallet->decrement('balance', $servicePrice);

            $transactionRef = 'TRX-' . strtoupper(Str::random(10));
            $performedBy = $user->first_name . ' ' . $user->last_name;

            // Clean API response for comment
            $cleanResponse = $this->cleanApiResponse($data);

            // Create transaction
            $transaction = Transaction::create([
                'transaction_ref' => $transactionRef,
                'user_id' => $user->id,
                'amount' => $servicePrice,
                'description' => "NIN Agent service for {$serviceField->field_name}",
                'type' => 'debit',
                'status' => 'completed',
                'performed_by' => $performedBy,
                'metadata' => [
                    'service' => $serviceField->service->name,
                    'service_field' => $serviceField->field_name,
                    'nin' => $request->nin,
                    'tracking_id' => $request->tracking_id,
                ],
            ]);

            // Determine status from API response
            $status = $this->normalizeStatus($data['status'] ?? 'processing');

            // Create Agent Service Record
            $agentService = AgentService::create([
                'reference' => 'REF-' . strtoupper(Str::random(10)),
                'user_id' => $user->id,
                'service_id' => $serviceField->service_id,
                'service_field_id' => $serviceField->id,
                'field_code' => $serviceField->field_code,
                'transaction_id' => $transaction->id,
                'service_type' => $serviceType == 'validation' ? 'NIN_VALIDATION' : 'IPE',
                'nin' => $request->nin,
                'tracking_id' => $request->tracking_id,
                'amount' => $servicePrice,
                'status' => $status,
                'submission_date' => now(),
                'service_field_name' => $serviceField->field_name,
                'description' => $request->description ?? $serviceField->field_name,
                'comment' => $cleanResponse,
                'performed_by' => $performedBy,
            ]);

            DB::commit();
            return back()->with('success', 'Request submitted successfully. Status: ' . $status);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction Error: ' . $e->getMessage());
            return back()->with('error', 'System Error: Failed to record transaction. Please contact support.');
        }
    }

    public function checkStatus(Request $request, $id = null)
    {
        try {
            if ($id) {
                $agentService = AgentService::findOrFail($id);
            } else {
                $request->validate([
                    'nin' => 'required|string',
                ]);
                $agentService = AgentService::where(function($q) use ($request) {
                        $q->where('nin', $request->nin)->orWhere('tracking_id', $request->nin);
                    })
                    ->orderBy('created_at', 'desc')
                    ->firstOrFail();
            }

            $apiKey = env('AREWA_API_TOKEN');
            $apiBaseUrl = env('AREWA_BASE_URL');
            $url = rtrim($apiBaseUrl, '/') . '/nin/validation';
            
            $payload = [
                'description' => $agentService->description ?? "Status Check"
            ];

            // Determine Payload based on service type
            if (strtoupper($agentService->service_type) == 'NIN_VALIDATION') {
                $payload['nin'] = $agentService->nin;
                $payload['field_code'] = '015';
            } else {
                $payload['tracking_id'] = $agentService->tracking_id;
                $payload['field_code'] = '002';
            }

            $response = Http::withToken($apiKey)
                ->acceptJson()
                ->get($url, $payload);
            
            $apiResponse = $response->json();

            // Clean the API response
            $cleanResponse = $this->cleanApiResponse($apiResponse);

            // Prepare update data
            $updateData = [
                'comment' => $cleanResponse,
            ];

            // Determine status from API response
            if (isset($apiResponse['status'])) {
                $updateData['status'] = $this->normalizeStatus($apiResponse['status']);
            } elseif (isset($apiResponse['response'])) {
                $updateData['status'] = $this->normalizeStatus($apiResponse['response']);
            }

            // Update the agent service record
            $agentService->update($updateData);

            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => true,
                    'nin' => $agentService->nin,
                    'tracking_id' => $agentService->tracking_id,
                    'status' => $agentService->status,
                    'response' => $apiResponse,
                    'clean_comment' => $cleanResponse
                ]);
            }

            return back()->with('success', 'Status checked successfully. Current status: ' . $agentService->status);

        } catch (\Exception $e) {
            Log::error('Status Check Error: ' . $e->getMessage());

            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to check status: ' . $e->getMessage(),
                    'status' => 'error'
                ], 500);
            }
            return back()->with('error', 'Unable to complete the request. Please try again.');

        }
    }

    
    // Webhook receiver
    public function webhook(Request $request)
    {
        $data = $request->all();

        Log::info('NIN Validation Webhook Received', $data);

        $identifier = $data['nin'] ?? $data['tracking_id'] ?? null;

        if ($identifier) {
            $submission = AgentService::where(function($q) use ($identifier) {
                    $q->where('nin', $identifier)->orWhere('tracking_id', $identifier);
                })
                ->orderBy('created_at', 'desc')
                ->first();

            if ($submission) {
                // Clean the webhook response
                $cleanResponse = $this->cleanApiResponse($data);
                
                $updateData = [
                    'comment' => $cleanResponse,
                ];

                if (isset($data['status'])) {
                    $updateData['status'] = $this->normalizeStatus($data['status']);
                }

                $submission->update($updateData);

                Log::info('NIN Validation Updated via Webhook', [
                    'submission_id' => $submission->id,
                    'identifier' => $identifier,
                    'new_status' => $updateData['status'] ?? 'unknown'
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Webhook received successfully'
        ]);
    }

    /**
     * Clean API response by removing unwanted characters
     */
    private function cleanApiResponse($response): string
    {
        if (is_array($response)) {
            // Keep only meaningful data, remove status if it's external
            $toKeep = array_diff_key($response, array_flip(['status', 'message', 'response']));
            return json_encode($toKeep);
        }

        return (string) $response;
    }

    private function normalizeStatus($status): string
    {
        $s = strtolower(trim((string) $status));
        
        return match ($s) {
            'successful', 'success', 'resolved', 'approved', 'completed' => 'successful',
            'processing', 'in_progress', 'in-progress', 'pending', 'submitted', 'new' => 'processing',
            'failed', 'rejected', 'error', 'declined', 'invalid', 'no record' => 'failed',
            default => 'pending',
        };
    }
}