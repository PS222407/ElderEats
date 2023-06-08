<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomepageController::class, 'index'])->name('welcome');

Route::delete('/account/{id}', [AccountController::class, 'destroy'])->name('account.destroy');
Route::get('/account/get-temporary-token', [AccountController::class, 'getTempToken'])->name('account.get-temporary-token');
Route::post('/account/attach-user', [AccountController::class, 'attachUser']);
Route::put('/account', [AccountController::class, 'update'])->name('account.update');

Route::post('/products', [ProductController::class, 'store']);
Route::post('/products/{ean}/add-to-shopping-list', [ProductController::class, 'addToShoppingList']);
Route::delete('/products/{pivotId}/detach', [ProductController::class, 'detach'])->name('products.pivotId.detach');

Route::post('/add-manually-product', [ProductController::class, 'addManualProduct']);
Route::post('/add-manually-product-shoppinglist', [ProductController::class, 'addManualProductShoppingList']);
Route::post('/add-manually-existing-product/{id}', [ProductController::class, 'addManualExistingProduct'])->name('product.add-manual-existing-product');
Route::post('/add-manually-existing-product-shoppinglist/{id}', [ProductController::class, 'addManualExistingProductShoppingList'])->name('product.add-manual-existing-product-shoppinglist');


Route::get('/test', function (\Illuminate\Http\Request $request) {
    Log::channel('api')->info($request->all());
});

//Route::post('/broadcasting/auth', function () {
//    return \App\Classes\Account::$accountModel;
//});
