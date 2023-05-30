<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PusherController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('/v1/product', [ProductController::class, 'store'])->name('product.store')->middleware('account.exists');
Route::delete('/v1/product', [ProductController::class, 'destroy'])->name('product.destroy')->middleware('account.exists');

Route::post('/v1/account-connection', [AccountController::class, 'incomingUser'])->middleware('account.exists');
Route::post('/v1/code', [AccountController::class, 'requestCode']);

Route::get('/v1/pusher/{id}', [PusherController::class, 'getById']);
