<?php

namespace App\Http\Controllers;

use App\Classes\Account;
use App\Enums\ConnectionStatus;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
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

        $row = DB::table('account_products')->where('id', $pivotId)->first();
        $ean = Product::find($row->product_id)->barcode;

        if ($authorized) {
            DB::table('account_products')->where('id', $pivotId)->delete();
        }

        Session::flash('popup', 'add-to-shopping-cart');
        Session::flash('ean', $ean);

        return redirect()->route('welcome')->with('popup', 'add-to-shopping-cart');
    }

    public function addToShoppingList(Request $request)
    {
        $product = Product::firstWhere('barcode', $request->ean);

        if (!$product) {
            return response()->json(['status' => 'failed', 'message' => 'product not found'], 404);
        }

        Account::$accountModel->shoppingList()->attach($product, ['is_active' => true]);

        return response()->json(['status' => 'success', 'message' => 'success']);
    }
}
