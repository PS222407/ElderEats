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
Route::post('/account/attach-user', [AccountController::class, 'attachUser'])->name('account.attach-user');
Route::post('/account/accept-or-deny-user', [AccountController::class, 'acceptOrDenyUser'])->name('account.accept-or-deny-user');

Route::delete('/product/account/detach', [ProductController::class, 'detachProduct']);
Route::post('/product/account/add-to-shopping-list', [ProductController::class, 'addToShoppingList']);
Route::post('/product/{ean}', [ProductController::class, 'store']);

Route::view('/test', 'test');
