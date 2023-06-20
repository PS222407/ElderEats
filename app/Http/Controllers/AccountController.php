<?php

namespace App\Http\Controllers;

use App\Classes\Account;
use App\Enums\ConnectionStatus;
use App\Http\Requests\AttachUserRequest;
use App\Http\Requests\UpdateAccountRequest;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AccountController extends Controller
{
    public function destroy(int $id)
    {
        Account::$accountEntity->users()->detach([$id]);

        return redirect('/');
    }

    public function getTempToken()
    {
        $tempToken = Account::generateTempToken();
        // $svg = QrCode::backgroundColor(0,0,0,0)->color(255,255,255)->size(250)->generate($tempToken);
        $svg = QrCode::size(250)->generate($tempToken);

        return response()->json([
            'tempToken' => $tempToken,
            'tempTokenQR' => $svg->toHtml(),
        ]);
    }

    public function attachUser(AttachUserRequest $request)
    {
        $usersInProcess = Account::$accountEntity->usersInProcess->pluck('id')->toArray();

        if (in_array((int)$request->userId, $usersInProcess, true)) {
            Account::$accountEntity->usersInProcess()->updateExistingPivot($request->userId, [
                'status' => ConnectionStatus::CONNECTED,
            ]);

            return response()->json(['status' => 'success', 'message' => 'user successfully attached to account']);
        }

        return response()->json(['status' => 'failed', 'message' => 'user could not be attached to account']);
    }

    public function update(UpdateAccountRequest $request)
    {
        Account::$accountEntity->update($request->validated());

        return redirect('/');
    }
}
