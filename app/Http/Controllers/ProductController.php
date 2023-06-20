<?php

namespace App\Http\Controllers;

use App\Classes\Account;
use App\Http\Requests\StoreProductManualRequest;
use App\Http\Requests\StoreProductRequest;
use App\Mail\NewProductAdded;
use App\Models\Product;
use App\Rules\Barcode;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Validator;

class ProductController extends Controller
{
    public function store(StoreProductRequest $request)
    {
        $response = Http::withHeaders(['x-api-key' => Account::$accountModel->token])->post(config('app.api_base_url') . '/Products', [
            'barcode' => $request->validated('ean'),
            'name' => $request->validated('name'),
            'amount' => $request->validated('amount'),
        ]);

        $response = $response->json();
        $accountId = Account::$accountModel->id;
        $productId = $response['id'];
        $quantity = $request['amount'];

        for ($i = 0; $i < $quantity; $i++) {
            Http::withHeaders(['x-api-key' => Account::$accountModel->token])->put(config('app.api_base_url') . "/Accounts/{$accountId}/Products/{$productId}/Create", [
                'accountId' => $accountId,
                'productId' => $productId,
            ]);
        }
        Session::flash('type', 'success');

        return redirect()->route('welcome');
    }

    public function addToShoppingList(int $ean)
    {
        Validator::validate(['ean' => $ean], ['ean' => ['required', new Barcode()]]);
        $product = Http::withHeaders(['x-api-key' => Account::$accountModel->token])->get(config('app.api_base_url') . '/Products/Product/Barcode/' . $ean);
        $product = $product->json();

        if (!$product) {
            return response()->json(['status' => 'failed', 'message' => 'product not found'], 404);
        }

        $accountId = Account::$accountModel->id;
        $productId = $product['id'];

        $response = Http::withHeaders(['x-api-key' => Account::$accountModel->token])->put(config('app.api_base_url') . "/Accounts/{$accountId}/FixedProducts/{$productId}/RanOut");
        if ($response->notFound()) {

            return response()->json(['status' => 'failed', 'message' => 'product not added to the shoppinglist', 404]);
        }

        return response()->json(['status' => 'success', 'message' => 'success']);
    }

    public function detach(int $pivotId)
    {
        $response = Http::withHeaders(['x-api-key' => Account::$accountModel->token])->put(config('app.api_base_url') . "/Accounts/Products/{$pivotId}/RanOut");

        $product = Http::withHeaders(['x-api-key' => Account::$accountModel->token])->get(config('app.api_base_url') . "/Products/Product/Connection/{$pivotId}");
        try {
            $ean = $product['barcode'];
        } catch (Exception $e) {
            return redirect()->route('welcome')->with('error-popup', 'Geen gekoppeld product gevonden');
        }

        if ($response->getStatusCode() == 500) {
            return redirect()->route('welcome')->with('error-popup', 'Geen gekoppeld product gevonden');
        }
        Session::flash('ean', $ean);
        return redirect()->route('welcome')->with('popup', 'add-to-shopping-cart');
    }

    public function addManualProduct(StoreProductManualRequest $request)
    {
        $product = Http::withHeaders(['x-api-key' => Account::$accountModel->token])->post(config('app.api_base_url') . '/Products', [
            'name' => $request->validated('name'),
        ]);

        $product = $product->json();
        $productId = $product['id'];
        $accountId = Account::$accountModel->id;

        Http::withHeaders(['x-api-key' => Account::$accountModel->token])->put(config('app.api_base_url') . "/Accounts/{$accountId}/Products/{$productId}/Create", [
            'accountId' => $accountId,
            'productId' => $productId,
        ]);

        Session::flash('type', 'success');

        return redirect('/');
    }

    public function addManualExistingProduct(int $id)
    {
        $product = Http::withHeaders(['x-api-key' => Account::$accountModel->token])->get(config('app.api_base_url') . '/Products/' . $id);
        $product = $product->json();
        if (!$product) {
            return redirect('/');
        }

        $productId = $product['id'];
        $accountId = Account::$accountModel->id;

        Http::withHeaders(['x-api-key' => Account::$accountModel->token])->put(config('app.api_base_url') . "/Accounts/{$accountId}/Products/{$productId}/Create", [
            'accountId' => $accountId,
            'productId' => $productId,
        ]);

        try {
            foreach (Account::$accountEntity->usersConnected as $recipient) {
                Mail::to($recipient)->send(new NewProductAdded());
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        Session::flash('type', 'success');

        return redirect('/');
    }

    public function addManualExistingProductShoppingList(int $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return redirect('/');
        }

        \App\Models\Account::find(Account::$accountEntity->id)->shoppingListWithoutTimestamps()->attach($id, ['is_active' => 1]);

        Session::flash('type', 'success');

        return redirect('/');
    }
}
