<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use App\Models\User;
use App\Services\MessageService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MessageController extends Controller
{
    private MessageService $messageService;
    
    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    public function create(User $user)
    {
        return view(
            'messages.create', 
            compact(
                'user'
            )
        );
    }

    public function store(CreateMessageRequest $request): RedirectResponse
    {
        Message::query()->create($request->validated());        

        return redirect()
            ->route('users.index')
            ->with('message', 'Message sent successfully');
    }

    public function apiIndex(Request $request): JsonResource
    {
        /** @var Message[] $messages */
        $messages = Message::query()
            ->select('messages.*', 's.username AS sender', 'r.username AS receiver')
            ->leftJoin('users AS s', 'messages.sender_id', '=', 's.id')
            ->leftJoin('users AS r', 'messages.receiver_id', '=', 'r.id')
            ->where('sender_id', Auth::id())
            ->orWhere(function (Builder $query) {
                $query->where('receiver_id', Auth::id())
                      ->where('is_hidden',  false);
            })
            ->get();

        $messageIds = [];
        foreach ($messages as $message) {
            if (!$message->isRead()) {
                $messageIds[] = $message->getId();
            }
        }

        $this->messageService->markAsRead($messageIds);

        return MessageResource::collection($messages);
    }

    public function index(): View
    {
        return view('messages.index');
    }

    public function hide(Message $message): RedirectResponse
    {
        $message->hide();
        
        return redirect()
            ->route('messages.index')
            ->with('message', 'Message hidden successfully');
    }
}
