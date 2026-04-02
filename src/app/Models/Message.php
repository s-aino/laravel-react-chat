<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable = ['text', 'is_me', 'is_read', 'is_deleted', 'user_id'];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
