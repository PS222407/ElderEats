<?php

namespace App\Http\Controllers;

use App\Classes\Account;
use App\Enums\ConnectionStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AccountController extends Controller
{
    public function getTempToken()
    {
        $tempToken = Account::generateTempToken();
        $svg = QrCode::generate($tempToken);

        return response()->json([
            'tempToken' => $tempToken,
            'tempTokenQR' => $svg->toHtml(),
        ]);
    }

    public function hasComingRequest()
    {
        $usersInProcess = Account::$accountModel->usersInProcess;
        $user = $usersInProcess->last();

        if (!$user) {
            return response()->json(['status' => 'failed', 'message' => 'No users found']);
        }

        return response()->json([
            'status' => 'success',
            'userName' => $user->name,
            'userId' => $user->id,
        ]);
    }

    public function acceptOrDenyUser(Request $request)
    {
        if ($request->isConfirmed === true || $request->isConfirmed === false) {
            $account = Account::$accountModel->usersInProcess()->withPivot('status')->where('user_id', $request->userId)->first();

            if ($account) {
                if ($request->isConfirmed === true) {
                    Account::$accountModel->usersInProcess()->updateExistingPivot($request->userId, [
                        'status' => ConnectionStatus::CONNECTED,
                    ]);

                    return response()->json(['status' => 'success', 'message' => 'user connected']);
                } elseif ($request->isConfirmed === false) {
                    Account::$accountModel->usersInProcess()->updateExistingPivot($request->userId, [
                        'status' => ConnectionStatus::REFUSED,
                    ]);

                    return response()->json(['status' => 'success', 'message' => 'user refused']);
                }
            }

            return response()->json(['status' => 'failed', 'message' => 'user not found']);
        }

        return response()->json(['status' => 'failed', 'message' => 'unknown']);
    }

    public function detachProduct(Request $request)
    {
        $accountProductPivots = Account::$accountModel->products()->withPivot(['id'])->get()->pluck('pivot.id')->toArray();
        $pivotId = (int)$request->pivot_id;
        $authorized = in_array($pivotId, $accountProductPivots);

        if ($authorized) {
            DB::table('account_products')->where('id', $pivotId)->delete();
        }

        return redirect()->route('welcome');
    }
}
