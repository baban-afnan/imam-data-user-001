<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::where('user_id', Auth::id());

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('service_type')) {
             // Assuming service_type filtering logic matches 'description' or a specific column if it existed.
             // Based on the dropdown, it seems to filter by service but the model in WalletController only showed description.
             // I'll filter by description looking for the service name for now, or if there's a specific column I missed.
             // Looking at the view `request('service_type')`, it passes a value.
             // For now, I'll assume we might need to search description or just leave it as a placeholder if column doesn't exist.
             // However, to be useful, let's search description for the service type if it makes sense, or exact match if a column exists.
             // Since I defined the model with standard fields, I'll filter description using 'like'.
             $query->where('description', 'like', '%' . $request->service_type . '%');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->latest()->paginate(10);

        return view('transactions.index', compact('transactions'));
    }
}
