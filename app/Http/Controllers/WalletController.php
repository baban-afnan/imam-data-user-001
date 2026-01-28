<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VirtualAccount;
use App\Repositories\VirtualAccountRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;


class WalletController extends Controller
{
    /**
     * Show wallet dashboard
     */
    public function index()
    {
        $userId = Auth::id();

        $virtualAccount = VirtualAccount::where('user_id', $userId)->first();
        $wallet = Wallet::where('user_id', $userId)->first();

        $walletData = [
            'wallet_balance'    => $wallet->wallet_balance ?? 0,
            'bonus'             => $wallet->bonus ?? 0,
            'status'            => $wallet->status ?? 'inactive',
            'available_balance' => $wallet->available_balance ?? 0,
        ];

        return view('wallet.index', compact('virtualAccount', 'walletData'));
    }

    /**
     * Create Virtual Wallet
     */
    public function createWallet(Request $request)
    {
        $loginUserId = Auth::id(); 
        $user = User::find($loginUserId);

        // Check KYC details
        if (empty($user->bvn) || empty($user->phone_no)) {
            return redirect()->route('wallet')->with([
                'error' => 'Please complete your registration by providing your BVN and Phone Number to open a virtual account.'
            ]);
        }

        // Repository call
        $repObj2 = new VirtualAccountRepository;
        $result = $repObj2->createVirtualAccount($loginUserId);

        // Handle failure
        if (!is_array($result) || !isset($result['success']) || !$result['success']) {
            $message = is_array($result) && isset($result['message'])
                ? $result['message']
                : 'Wallet creation failed. Please try again later.';

            return redirect()->route('wallet')->with(['error' => $message]);
        }

        // Success
        return redirect()->route('wallet')->with(['success' => $result['message']]);
    }

    /**
     * Claim bonus: move bonus to wallet_balance and record transaction
     */
    public function claimBonus(Request $request)
    {
        $userId = Auth::id();
        $wallet = Wallet::where('user_id', $userId)->lockForUpdate()->first();

        if (!$wallet || $wallet->bonus <= 0) {
            return redirect()->route('wallet')->with(['error' => 'No bonus available to claim.']);
        }

        DB::transaction(function () use ($wallet, $userId) {
            $bonusAmount = $wallet->bonus;

            // Update wallet balances
            $wallet->wallet_balance += $bonusAmount;
            $wallet->available_balance += $bonusAmount;
            $wallet->bonus = 0;
            $wallet->save();

            // Performed by
            $user = User::find($userId);
            $performedBy = $user ? $user->first_name . ' ' . $user->last_name : 'System';

            // Save transaction
            Transaction::create([
                'user_id'         => $userId,
                'type'            => 'credit',
                'amount'          => $bonusAmount,
                'description'     => 'Bonus claimed and credited to wallet balance',
                'status'          => 'completed',
                'transaction_ref' => 'BONUS-' . strtoupper(uniqid()),
                'performed_by'    => $performedBy,
            ]);
        });

        return redirect()->route('wallet')->with(['success' => 'Bonus successfully claimed and added to your wallet balance.']);
    }

}
