<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\ProductController;
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

Route::view('/', 'welcome')->name('welcome');

Route::get('/account/get-temporary-token', [AccountController::class, 'getTempToken'])->name('account.get-temporary-token');
Route::get('/account/has-coming-request', [AccountController::class, 'hasComingRequest'])->name('account.has-coming-request');
Route::post('/account/accept-or-deny-user', [AccountController::class, 'acceptOrDenyUser'])->name('account.accept-or-deny-user');
Route::delete('/account/product', [AccountController::class, 'detachProduct']);

Route::post('/account/add-to-shopping-list', [ProductController::class, 'addToShoppingList']);
Route::post('/product/{ean}', [ProductController::class, 'store']);

Route::get('/test', function () {
   return view('test', [
       'products' => \App\Classes\Account::$accountModel->products,
   ]);
});
