<?php

namespace App\Http\Controllers\Api;

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
        $accountProducts = Account::firstWhere('token', $request->account_token)->products();

        if (!$product) {
            $response = Http::get(sprintf('https://world.openfoodfacts.org/api/v3/product/%s.json', $request->barcode), [
                'fields' => 'product_name,image_url,brands,quantity',
            ]);
            $json = $response->json();
            if ($json['errors'] != []) {
                return response()->json(['status' => 'failed', 'message' => 'product not found in api or database']);
            }

            $accountProducts->create([
                'name' => $json['product']['product_name'],
                'brand' => $json['product']['brands'],
                'quantity_in_package' => $json['product']['quantity'],
                'barcode' => $request->barcode,
                'image' => $json['product']['image_url'],
            ], [
                'expiration_date' => now(),
            ]);
        } else {
            $accountProducts->attach($product, [
                'expiration_date' => now(),
            ]);
        }

        return response()->json(['status' => 'success', 'message' => 'product added successfully']);
    }
}
