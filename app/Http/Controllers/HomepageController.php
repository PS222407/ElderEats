<?php

namespace App\Http\Controllers;

use App\Classes\Account;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    public function index()
    {
        return view('welcome', [
            'connectedUsers' => Account::$accountModel->users,
        ]);
    }
}
