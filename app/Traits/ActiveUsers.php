<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait ActiveUsers
{
    //Verify Active Users
    public function is_active()
    {
        $is_active = Auth::user()->is_active;

        return $is_active;
    }
}
