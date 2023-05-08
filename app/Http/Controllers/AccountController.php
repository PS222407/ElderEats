<?php

namespace App\Http\Controllers;

use App\Classes\Account;
use App\Enums\ConnectionStatus;
use App\Http\Requests\AttachUserRequest;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AccountController extends Controller
{
    public function destroy(int $id)
    {
        Account::$accountModel->users()->detach([$id]);

        return redirect('/');
    }

    public function getTempToken()
    {
        $tempToken = Account::generateTempToken();
//        $svg = QrCode::backgroundColor(0,0,0,0)->color(255,255,255)->size(250)->generate($tempToken);
        $svg = QrCode::size(250)->generate($tempToken);

        return response()->json([
            'tempToken' => $tempToken,
            'tempTokenQR' => $svg->toHtml(),
        ]);
    }

    public function attachUser(AttachUserRequest $request)
    {
        $usersInProcess = Account::$accountModel->usersInProcess->pluck('id')->toArray();

        if (in_array($request->userId, $usersInProcess)) {
            Account::$accountModel->usersInProcess()->updateExistingPivot($request->userId, [
                'status' => ConnectionStatus::CONNECTED,
            ]);
            return response()->json(['status' => 'success', 'message' => 'user successfully attached to account']);
        }

        return response()->json(['status' => 'failed', 'message' => 'user could not be attached to account']);
    }
}
