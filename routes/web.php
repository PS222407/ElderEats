<?php

use App\Classes\Account;
use App\Http\Controllers\AccountController;
use App\Models\User;
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

Route::view('/', 'welcome');

Route::get('/account/get-temporary-token', [AccountController::class, 'getTempToken'])->name('account.get-temporary-token');
Route::get('/account/has-coming-request', [AccountController::class, 'hasComingRequest'])->name('account.has-coming-request');

