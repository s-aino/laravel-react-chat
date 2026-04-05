<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Message;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'user1_id',
        'user2_id',
    ];

    // この部屋に属するメッセージ
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // user1（小さいID側）
    public function user1()
    {
        return $this->belongsTo(User::class, 'user1_id');
    }

    // user2（大きいID側）
    public function user2()
    {
        return $this->belongsTo(User::class, 'user2_id');
    }
}