<?php

namespace App\Http\Controllers;

use App\Classes\Account;
use App\Enums\ConnectionStatus;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AccountController extends Controller
{
    public function getTempToken()
    {
        $tempToken = Account::generateTempToken();
        $svg = QrCode::backgroundColor(0,0,0,0)->color(255,255,255)->size(250)->generate($tempToken);

        return response()->json([
            'tempToken' => $tempToken,
            'tempTokenQR' => $svg->toHtml(),
        ]);
    }

    public function attachUser(Request $request)
    {
        $usersInProcess = Account::$accountModel->usersInProcess->pluck('id')->toArray();

        if (in_array($request->userId, $usersInProcess)) {
            Account::$accountModel->usersInProcess()->updateExistingPivot($request->userId, [
                'status' => ConnectionStatus::CONNECTED,
            ]);
        }

        return response()->json(['status' => 'success', 'message' => 'user successfully attached to account']);
    }
}
