<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Message;

class MessageService
{
    public function markAsRead(array $messageIds): void
	{
        /** @var Message[] $messages */
        $messages = Message::query()
            ->whereIn('id', $messageIds)
            ->get();
            
		foreach ($messages as $message) {
			$message->markAsRead();
		}
	}
}
