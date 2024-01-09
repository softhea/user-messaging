<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'receiver_id',
        'message',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::creating(function (Message $message) {
            $message->sender_id = Auth::id();
        });
    }
}
