<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallet;
use App\Models\User;
use App\Models\BonusHistory;
use App\Models\VirtualAccount;
use App\Models\Transaction;
use App\Models\AgentService;
use App\Models\Announcement;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $wallet = Wallet::where('user_id', $user->id)->first();
        $announcement = Announcement::getActiveAnnouncement();

        $virtualAccount = VirtualAccount::where('user_id', $user->id)->first();
        $bonusHistory = BonusHistory::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Date Filtering Logic
        $isFiltered = $request->has('start_date') && $request->has('end_date');

        if ($isFiltered) {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
        } else {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        }

        // 1. Total Transaction Amount (Debit)
        $totalTransactionAmount = Transaction::where('user_id', $user->id)
            ->where('type', 'debit')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        // 2. Total Agency Requests
        $totalAgencyRequests = AgentService::where('user_id', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // 3. Total Funded Amount (Credit)
        $totalFundedAmount = Transaction::where('user_id', $user->id)
            ->where('type', 'credit')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        // 4. Total Referrals
        // Default to All Time if not filtered, otherwise use filter range
        $referralQuery = BonusHistory::where('user_id', $user->id);
        
        if ($isFiltered) {
            $referralQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        
        $totalReferrals = $referralQuery->count();

        // 5. Recent 10 Transactions
        $recentTransactions = Transaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // 6. Transaction Statistics
        $transactionStats = Transaction::where('user_id', $user->id)
            ->selectRaw('count(*) as total')
            ->selectRaw("count(case when status = 'completed' then 1 end) as completed")
            ->selectRaw("count(case when status = 'pending' then 1 end) as pending")
            ->selectRaw("count(case when status = 'failed' then 1 end) as failed")
            ->first();

        $totalTransactions = $transactionStats->total ?? 0;
        $completedTransactions = $transactionStats->completed ?? 0;
        $pendingTransactions = $transactionStats->pending ?? 0;
        $failedTransactions = $transactionStats->failed ?? 0;

        // Calculate percentages
        $completedPercentage = $totalTransactions > 0 ? round(($completedTransactions / $totalTransactions) * 100) : 0;
        $pendingPercentage = $totalTransactions > 0 ? round(($pendingTransactions / $totalTransactions) * 100) : 0;
        $failedPercentage = $totalTransactions > 0 ? round(($failedTransactions / $totalTransactions) * 100) : 0;

        return view('dashboard', compact(
            'user', 
            'wallet', 
            'virtualAccount', 
            'bonusHistory',
            'totalTransactionAmount',
            'totalAgencyRequests',
            'totalFundedAmount',
            'totalReferrals',
            'isFiltered',
            'startDate',
            'endDate',
            'recentTransactions',
            'totalTransactions',
            'completedTransactions',
            'pendingTransactions',
            'failedTransactions',
            'completedPercentage',
            'pendingPercentage',
            'failedPercentage',
            'announcement'
        ));
    }
}
