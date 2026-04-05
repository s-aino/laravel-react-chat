<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Message;
use App\Models\Room;

// 一覧取得
Route::get('/rooms/{room}/messages', function (Room $room) {
    return Message::with('user')
        ->where('room_id', $room->id)
        ->orderBy('id')
        ->get();
});

Route::post('/rooms/{room}/messages', function (Request $request, Room $room) {
    $validated = $request->validate([
        'user_id' => ['required', 'exists:users,id'],
        'text' => ['required', 'string', 'max:255'],
    ]);

    $message = Message::create([
        'room_id' => $room->id,
        'user_id' => $validated['user_id'],
        'text' => $validated['text'],
        'is_read' => false,
        'is_deleted' => false,
    ]);

    return $message->load('user');
});

Route::patch('/messages/{id}', function ($id) {
    $message = Message::findOrFail($id);

    $message->update([
        'is_deleted' => true,
    ]);

    return response()->json($message);
});

Route::patch('/messages/{id}/like', function ($id) {
    $message = Message::findOrFail($id);

    $message->update([
        'is_liked' => !$message->is_liked,
    ]);

    return response()->json($message);
});

Route::patch('/rooms/{room}/read', function (Request $request, Room $room) {
    $userId = $request->input('user_id');
    Message::where('room_id', $room->id)
        ->where('user_id', '!=', $userId)
        ->where('is_read', false)
        ->update([
            'is_read' => true,
        ]);

    return response()->json([
        'message' => '既読更新完了',
    ]);
});
