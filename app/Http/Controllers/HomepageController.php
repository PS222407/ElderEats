<?php

namespace App\Http\Controllers;

use App\Classes\Account;

class HomepageController extends Controller
{
    public function index()
    {
        return view('welcome', [
            'connectedUsers' => Account::$accountEntity->loadConnectedUsers(),
        ]);
    }
}
