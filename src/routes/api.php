<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Message;

// 一覧取得
Route::get('/messages', function () {
    return Message::with('user')->orderBy('id')->get();
});

Route::post('/messages', function (Request $request) {
    $request->validate([
        'text' => ['required', 'string'],
        'user_id' => ['required', 'exists:users,id'],
    ]);

    $message = Message::create([
        'text' => $request->text,
        'user_id' => $request->user_id,
        'is_read' => false,
        'is_deleted' => false,
    ]);

    Message::where('user_id', '!=', $request->user_id)
        ->where('is_read', false)
        ->update(['is_read' => true]);

    return response()->json($message);
});
Route::patch('/messages/{id}', function ($id) {
    $message = Message::findOrFail($id);

    $message->update([
        'is_deleted' => true,
    ]);

    return response()->json($message);
});
