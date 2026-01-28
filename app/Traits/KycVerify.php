<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait KycVerify
{
    //Verify Users Kyc
    public function is_verified()
    {
        $verify_status = Auth::user()->kyc_status;

        return $verify_status;
    }
}
