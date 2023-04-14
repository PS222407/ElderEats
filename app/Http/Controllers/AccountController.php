<?php

namespace App\Http\Controllers;

use App\Classes\Account;
use App\Models\Account as AccountModel;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function getTempToken()
    {
        $tempToken = Account::generateTempToken();

        return response()->json(['tempToken' => $tempToken]);
    }

    public function hasComingRequest()
    {

    }
}
