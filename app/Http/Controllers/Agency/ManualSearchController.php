<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\ServiceField;
use App\Models\AgentService;
use App\Models\Transaction;
use App\Models\Service;
use App\Models\Wallet;

class ManualSearchController extends Controller
{
    /**
     * Display phone number submission page with submission history.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Ensure wallet exists
        $wallet = Wallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0.00, 'status' => 'active']
        );

        // Fetch all valid submissions (number not null/empty)
          $query = AgentService::where('user_id', $user->id)
        ->where('service_type', 'bvn_search');

        // Apply search filter
        if ($request->filled('search')) {
            $query->where('number', 'like', '%' . $request->search . '%');
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Custom ordering: pending → processing → others
        $query->orderByRaw("
            CASE 
                WHEN status = 'pending' THEN 1
                WHEN status = 'processing' THEN 2
                ELSE 3
            END
        ")->orderByDesc('submission_date');

        // Paginate results
        $crmSubmissions = $query->paginate(5)->withQueryString();

        // Fetch active phone search service
        $phoneService = Service::where('name', 'BVN SEARCH')
            ->where('is_active', true)
            ->first();

        // Load active fields for this service
        $serviceFields = $phoneService
            ? ServiceField::where('service_id', $phoneService->id)
                ->where('is_active', true)
                ->get()
            : collect();

        return view('bvn.phone-search', [
            'serviceFields'  => $serviceFields,
            'crmSubmissions' => $crmSubmissions,
            'services'       => Service::where('is_active', true)->get(),
            'bvnService'     => $phoneService,
            'wallet'         => $wallet,
        ]);
    }

    /**
     * Handle phone number submission and charge user based on selected service and role.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'service_field_id' => 'required|exists:service_fields,id',
            'number' => 'required|string|size:11|regex:/^[0-9]{11}$/',
        ]);

        $serviceField = ServiceField::with('service')->findOrFail($validated['service_field_id']);
        $serviceName = $serviceField->service->name ?? 'Unknown Service';
        $servicePrice = $serviceField->getPriceForUserType($user->role);

        if ($servicePrice === null) {
            return back()->with([
                'status' => 'error',
                'message' => 'Service price not configured for your user role.',
            ])->withInput();
        }

        $wallet = Wallet::where('user_id', $user->id)->firstOrFail();

        if ($wallet->status !== 'active') {
            return back()->with([
                'status' => 'error',
                'message' => 'Your wallet is inactive. Please contact support.',
            ])->withInput();
        }

        if ($wallet->balance < $servicePrice) {
            return back()->with([
                'status' => 'error',
                'message' => 'Insufficient wallet balance. You need NGN ' .
                    number_format($servicePrice - $wallet->balance, 2) . ' more.',
            ])->withInput();
        }

        DB::beginTransaction();

        try {
            $transactionRef = 'P1' . date('is') . strtoupper(Str::random(5));
            $performedBy = trim($user->first_name . ' ' . $user->last_name);

            // Create transaction record
            $transaction = Transaction::create([
                'transaction_ref' => $transactionRef,
                'user_id' => $user->id,
                'amount' => $servicePrice,
                'description' => "{$serviceName} for {$serviceField->field_name}",
                'type' => 'debit',
                'status' => 'completed',
                'performed_by' => $performedBy,
                'metadata' => [
                    'service' => 'phone_search',
                    'service_name' => $serviceName,
                    'service_field' => $serviceField->field_name,
                    'field_code' => $serviceField->field_code,
                    'number' => $validated['number'],
                    'user_role' => $user->role,
                ],
            ]);

            // Record submission
            AgentService::create([
                'reference' => $transactionRef,
                'user_id' => $user->id,
                'service_field_id' => $serviceField->id,
                'service_id' => $serviceField->service_id,
                'field_code' => $serviceField->field_code,
                'field_name' => $serviceField->field_name,
                'amount' => $servicePrice,
                'service_name' => $serviceName,
                'number' => $validated['number'],
                'transaction_id' => $transaction->id,
                'performed_by' => $performedBy,
                'submission_date' => now(),
                'status' => 'pending',
                'service_type' => 'bvn_search',
            ]);

            // Deduct from wallet
            $wallet->decrement('balance', $servicePrice);

            DB::commit();

            return redirect()->route('phone.search.index')->with([
                'status' => 'success',
                'message' => 'Phone number submitted successfully. Ref: ' . $transactionRef .
                    '. Charged NGN ' . number_format($servicePrice, 2),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            report($e);

            return back()->with([
                'status' => 'error',
                'message' => 'Submission failed: ' . $e->getMessage(),
            ])->withInput();
        }
    }

    /**
     * Fetch dynamic service field price based on user role.
     */
    public function getFieldPrice(Request $request)
    {
        $request->validate([
            'field_id' => 'required|exists:service_fields,id',
        ]);

        $user = Auth::user();
        $field = ServiceField::findOrFail($request->field_id);
        $price = $field->getPriceForUserType($user->role);

        return response()->json([
            'success' => true,
            'price' => $price,
            'formatted_price' => 'NGN ' . number_format($price, 2),
            'field_name' => $field->field_name,
            'base_price' => $field->base_price,
        ]);
    }
}
