<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use App\Models\BonusHistory;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Show registration page
     */
    public function create($referral = null): View
    {
        return view('auth.register', ['referral' => $referral]);
    }

    /**
     * Handle registration
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email'         => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password'      => ['required', 'confirmed', Rules\Password::defaults()],
            'referral_code' => ['nullable', 'string', 'max:50'],
            'terms'   => ['accepted'],
        ]);

        DB::beginTransaction();

        try {
            // Get referral details (if any)
            $referralDetails = $this->getBonus($request);
            if (isset($referralDetails['error'])) {
                return back()->withInput()->with('error', $referralDetails['error']);
            }

            // Create user
            $user = User::create([
                'email'         => $request->email,
                'password'      => Hash::make($request->password),
                'referral_code' => $referralDetails['myOwnCode'],
            ]);

            // Create wallet
            Wallet::create([
                'user_id'           => $user->id,
                'balance'           => 0.00,
                'hold_amount'       => 0.00,
                'available_balance' => 0.00,
                'wallet_number'     => (string) random_int(1000000000, 9999999999),
                'currency'          => 'NGN',
                'status'            => 'active',
                'last_activity'     => now(),
                'bonus'             => 0.00,
            ]);

            // Add referral bonus if applicable
            if ($referralDetails['referral_id']) {
                $this->addBonus($referralDetails['referral_id'], $referralDetails['referral_bonus'], $user->id);
            }

            DB::commit();

            event(new Registered($user));
            Auth::login($user);

            return redirect()->route('dashboard')->with('success', 'Account created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Registration failed: ' . $e->getMessage());
        }
    }

    /**
     * Process referral bonus info
     */
    private function getBonus(Request $request): array
    {
        $referral_id = null;
        $referral_bonus = 0.00;

        if ($request->filled('referral_code')) {
            $referralUser = User::where('referral_code', $request->referral_code)->first();

            if ($referralUser) {
                $referral_id = $referralUser->id;
                $referral_bonus = $referralUser->referral_bonus > 0
                    ? $referralUser->referral_bonus
                    : (DB::table('referral_bonus')->value('bonus') ?? 0.00);
            } else {
                return ['error' => 'Invalid referral code.'];
            }
        }

        // Generate a unique referral code for the new user
        do {
            $myOwnCode = substr(md5(uniqid($request->email, true)), 0, 6);
        } while (User::where('referral_code', $myOwnCode)->exists());

        return [
            'referral_id'    => $referral_id,
            'referral_bonus' => $referral_bonus,
            'myOwnCode'      => $myOwnCode,
        ];
    }

    /**
     * Credit bonus to referrer
     */
    private function addBonus(int $referral_id, float $referral_bonus, int $referred_user_id): void
    {
        $wallet = Wallet::where('user_id', $referral_id)->first();

        if ($wallet) {
            $wallet->bonus = ($wallet->bonus ?? 0) + $referral_bonus;
            $wallet->save();

            BonusHistory::create([
                'user_id'          => $referral_id,
                'referred_user_id' => $referred_user_id,
                'amount'           => $referral_bonus,
                'type'             => 'referral',
            ]);
        }
    }
}
