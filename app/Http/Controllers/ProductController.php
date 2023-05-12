<?php

namespace App\Http\Controllers;

use App\Classes\Account;
use App\Http\Requests\StoreProductManualRequest;
use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use App\Rules\Barcode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Validator;

class ProductController extends Controller
{
    public function store(StoreProductRequest $request)
    {
        $product = Product::create([
            'barcode' => $request->validated('ean'),
            'name' => $request->validated('name'),
        ]);

        Account::$accountModel->products()->attach(array_fill(0, $request->validated('amount'), $product->id));

        Session::flash('type', 'success');

        return redirect()->route('welcome');
    }

    public function addToShoppingList(int $ean)
    {
        Validator::validate(['ean' => $ean], ['ean' => ['required', new Barcode()]]);

        $product = Product::firstWhere('barcode', $ean);

        if (!$product) {
            return response()->json(['status' => 'failed', 'message' => 'product not found'], 404);
        }

        Account::$accountModel->shoppingList()->attach($product, ['is_active' => true]);

        return response()->json(['status' => 'success', 'message' => 'success']);
    }

    public function detach(int $pivotId)
    {
        $accountPivotIds = Account::$accountModel->activeProducts->pluck('pivot.id')->toArray();
        $authorized = in_array($pivotId, $accountPivotIds);

        $row = DB::table('account_products')->where('id', $pivotId)?->first();
        if (!$row) {
            return redirect()->route('welcome')->with('error-popup', 'Geen gekoppeld product gevonden');
        }

        $ean = Product::find($row->product_id)->barcode;
        if ($authorized) {
            DB::table('account_products')->where('id', $pivotId)->update([
                'ran_out_at' => now(),
            ]);
        }

        Session::flash('ean', $ean);

        return redirect()->route('welcome')->with('popup', 'add-to-shopping-cart');
    }

    public function addManualProduct(StoreProductManualRequest $request)
    {
        Account::$accountModel->products()->create($request->validated());

        Session::flash('type', 'success');

        return redirect('/');
    }

    public function addManualExistingProduct(int $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return redirect('/');
        }

        Account::$accountModel->products()->attach($id);

        Session::flash('type', 'success');

        return redirect('/');
    }
}
