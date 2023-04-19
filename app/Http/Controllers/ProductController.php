<?php

namespace App\Http\Controllers;

use App\Classes\Account;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    public function store(string $ean, Request $request)
    {
        Account::$accountModel->products()->create([
            'name' => $request->name,
            'barcode' => $ean,
        ]);

        Session::flash('type', 'success');

        return redirect()->route('welcome');
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

    public function detachProduct(Request $request)
    {
        $accountProductPivots = Account::$accountModel->products()->withPivot(['id'])->get()->pluck('pivot.id')->toArray();
        $pivotId = (int)$request->pivot_id;
        $authorized = in_array($pivotId, $accountProductPivots);

        $row = DB::table('account_products')->where('id', $pivotId)->first();
        $ean = Product::find($row->product_id)->barcode;

        if ($authorized) {
            DB::table('account_products')->where('id', $pivotId)->update([
                'ran_out_at' => now(),
            ]);
        }

        Session::flash('popup', 'add-to-shopping-cart');
        Session::flash('ean', $ean);

        return redirect()->route('welcome')->with('popup', 'add-to-shopping-cart');
    }
}
