<?php

namespace App\Http\Controllers\Api;

use App\Events\UserAccountRequest;
use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function incomingUser(Request $request)
    {
        $account = Account::firstWhere('token', $request->account_token);
        $usersInProcess = $account->usersInProcess;
        $user = $usersInProcess->last();

        if (!$user) {
            return response()->json(['status' => 'failed', 'message' => 'No users found'], 404);
        }

        UserAccountRequest::dispatch($account->id, $user->id, $user->name);

        return response()->json(['status' => 'pending', 'message' => 'call made successfully, further processes are done asynchronously']);
    }
}