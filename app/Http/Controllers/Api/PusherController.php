<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pusher;

class PusherController extends Controller
{
    public function getById(int $id)
    {
        $pusher = Pusher::findOrFail($id);

        return response()->json(['status' => 'success', 'message' => 'account found, see token in token', 'pusherContent' => $pusher->content]);
    }
}
