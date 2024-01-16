<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * @property int $id;
 * @property bool $is_read;
 * @property bool $is_hidden
 */
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

    public function getId(): int
    {
        return (int) $this->id;
    }

    public function isRead(): bool
    {
        return (bool) $this->is_read;
    }

    public function markAsRead(): void
    {
        $this->is_read = true;
        $this->save();
    }

    public function hide(): void
    {
        $this->is_hidden = true;
        $this->save();
    }
}
