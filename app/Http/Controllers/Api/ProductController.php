<?php

namespace App\Http\Controllers\Api;

use App\Events\AddProductScanned;
use App\Events\DeleteProductScanned;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $product = Product::firstWhere('barcode', $request->barcode);
        $account = Account::firstWhere('token', $request->account_token);
        $productFound = false;

        if (!$product) {
            $response = Http::get(sprintf('https://world.openfoodfacts.org/api/v3/product/%s.json', $request->barcode), [
                'fields' => 'product_name,image_url,brands,quantity',
            ]);
            $json = $response->json();
            if ($json['errors'] == []) {
                $account->products()->create([
                    'name' => $json['product']['product_name'],
                    'brand' => $json['product']['brands'] ?? null,
                    'quantity_in_package' => $json['product']['quantity'] ?? null,
                    'barcode' => $request->barcode,
                    'image' => $json['product']['image_url'] ?? null,
                ]);

                $productFound = true;
            }
        } else {
            $account->products()->attach($product);
            $productFound = true;
        }

        AddProductScanned::dispatch($request->barcode, $account->id, $productFound);

        if ($productFound) {
            return response()->json(['status' => 'success', 'message' => 'product added successfully']);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'product not found']);
        }
    }

    public function destroy(Request $request)
    {
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
