<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MessageController extends Controller
{
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
        /** @var User $loggedUser */
        $loggedUser = Auth::user();

        $messages = Message::query()
            ->where('sender_id', $loggedUser->getId())
            ->orWhere('receiver_id', $loggedUser->getId())
            ->get();

        foreach ($messages as $message) {
            // $message->canBeHidden = !$message->isHidden() && $message->receiverId === Auth::id();
        }   

        // dd($messages);
        // $messageIds = [];
        // foreach ($messages as $message) {
        //     if (!$message->isRead) {
        //         $messageIds[] = $message->id;
        //     }
        // }

        // $messageService = new MessageService();
        // $messageService->markAsRead($messageIds);

        return MessageResource::collection($messages);
    }

    public function index(): View
    {
        return view('messages.index');
    }

    // public function edit(User $user, Request $request)
    // {
    //     /** @var User $loggedUser */
    //     $loggedUser = Auth::user();


    //     $canUpdateUser = $loggedUser->getRoleId() < $user->getRoleId()
    //         || Auth::id() === $user->getId();
    //     if (!$canUpdateUser) {
    //         return redirect()
    //             ->route('not_allowed')
    //             ->with('error', 'Not allowed to update this user!');
                
    //     }

    //     $roles = User::ROLES;
    //     unset($roles[User::ROLE_ID_SUPERADMIN]);
    //     foreach ($roles as $roleId => $roleName) {
    //         if (Auth::user()->role_id > $roleId) {
    //             unset($roles[$roleId]);
    //         }
    //     }

    //     $defaultRoleId = old('role_id') ?? $user->getRoleId();

    //     $title = "Update User " . $user->getUsername();
    //     $route = route('users.update', ['user' => $user->getId()]);

    //     return view(
    //         'users.create_update', 
    //         compact(
    //             'roles', 
    //             'defaultRoleId', 
    //             'title',
    //             'user',
    //             'route'
    //         )
    //     );
    // }

    // public function update(User $user, UpdateMessageRequest $request): RedirectResponse
    // {
    //     $user->update($request->validated());
        
    //     return redirect()
    //         ->route('users.index')
    //         ->with('message', 'User updated successfuly');
    // }
}
