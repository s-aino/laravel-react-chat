<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Message;
use App\Models\Room;
use App\Models\User;


// ルーム一覧取得
Route::get('/rooms', function (Request $request) {
    $userId = $request->query('user');

    $rooms = Room::where('user1_id', $userId)
        ->orWhere('user2_id', $userId)
        ->get()
        ->map(function ($room) use ($userId) {
            $otherUserId = $room->user1_id == $userId
                ? $room->user2_id
                : $room->user1_id;

            $otherUser = User::find($otherUserId);

            $lastMessage = Message::where('room_id', $room->id)
                ->where('is_deleted', false)
                ->orderByDesc('id')
                ->first();

            $unreadCount = Message::where('room_id', $room->id)
                ->where('user_id', '!=', $userId)
                ->where('is_read', false)
                ->where('is_deleted', false)
                ->count();

            return [
                'room_id' => $room->id,
                'user_id' => $otherUser->id,
                'name' => $otherUser->name,
                'last_message' => $lastMessage?->text ?? '',
                'unread_count' => $unreadCount,
            ];
        });

    return response()->json($rooms, 200, [], JSON_UNESCAPED_UNICODE);
});
// メッセージ一覧取得
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
