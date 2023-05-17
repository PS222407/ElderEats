<?php

namespace App\Http\Controllers\Api;

use App\Events\AddProductScanned;
use App\Events\DeleteProductScanned;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreProductRequest;
use App\Models\Account;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function store(StoreProductRequest $request)
    {
        Log::channel('api')->info($request->all());

        $product = Product::firstWhere('barcode', $request->barcode);
        $account = Account::firstWhere('token', $request->account_token);
        $amount = $request->has('amount') ? (int)$request->amount : 1;

        if (!$product) {
            $response = Http::get(sprintf('https://world.openfoodfacts.org/api/v3/product/%s.json', $request->barcode), [
                'fields' => 'product_name,image_url,brands,quantity',
            ]);
            $json = $response->json();
            if ($json['errors'] == []) {
                try {
                    $product = Product::create([
                        'name' => $json['product']['product_name'],
                        'brand' => $json['product']['brands'] ?? null,
                        'quantity_in_package' => $json['product']['quantity'] ?? null,
                        'barcode' => $request->barcode,
                        'image' => $json['product']['image_url'] ?? null,
                    ]);
                } catch (Exception $e) {
                    $product = false;
                }
            }
        }

        if ($product) {
            $account->products()->attach(array_fill(0, $amount, $product->id));
        }

        AddProductScanned::dispatch($request->barcode, $account->id, (bool)$product, $amount);

        if ($product) {
            return response()->json(['status' => 'success', 'message' => 'product added successfully']);
        }
        return response()->json(['status' => 'warning', 'message' => 'product not found, barcode is valid']);
    }

    public function destroy(Request $request)
    {
        Log::channel('api')->info($request->all());

        $product = Product::firstWhere('barcode', $request->barcode);
        if (!$product) {
            return response()->json(['status' => 'failed', 'message' => 'product not found']);
        }

        $account = Account::firstWhere('token', $request->account_token);
        $products = $account->activeProducts()->where('product_id', $product->id)->get()->toArray();

        DeleteProductScanned::dispatch($products, $account->id);

        return response()->json(['status' => 'pending', 'message' => 'call made successfully, further processes are done asynchronously']);
    }
}
