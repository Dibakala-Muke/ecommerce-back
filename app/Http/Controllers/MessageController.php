<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index($userId) {
        return Message::where('user_id', $userId)->get();
    }

    public function store(Request $request) {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'sender' => 'required|string',
            'message' => 'required|string'
        ]);

        return Message::create($data);
    }
}
