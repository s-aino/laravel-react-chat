<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Message;

// 一覧取得
Route::get('/messages', function () {
    return Message::orderBy('id')->get();
});

Route::post('/messages', function (Request $request) {
    $request->validate([
        'text' => ['required', 'string'],
        'is_me' => ['required', 'boolean'],
    ]);

    $isMe = filter_var($request->is_me, FILTER_VALIDATE_BOOLEAN);

    $message = Message::create([
        'text' => $request->text,
        'is_me' => $isMe,
        'is_read' => false,
    ]);

    Message::where('is_me', !$isMe)
        ->where('is_read', false)
        ->update(['is_read' => true]);

    return response()->json($message);
});
