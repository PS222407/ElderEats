<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function store(string $ean, Request $request)
    {
        Product::create([
            'name' => $request->name,
            'barcode' => $ean,
        ]);

        return redirect()->route('welcome');
    }
}
