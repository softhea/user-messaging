<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller
{
    public function apiIndex(Request $request): JsonResource
    {
        /** @var User[] $users */
        $users = User::query()
            ->where(
                'role_id', 
                '>=', 
                Auth::user()->role_id
            )->get();
        foreach ($users as $user) {
            $user->canBeUpdated = 
                Auth::user()->role_id < $user->role_id
                || Auth::id() === $user->id; 
            $user->canBeDeleted = Auth::user()->role_id < $user->role_id;
        }

        return UserResource::collection($users);
    }

    public function index(Request $request): View
    {
        $canCreateUsers = Auth::user()->roleId <= User::ROLE_ID_ADMIN;
        $error = '';
        $message = '';

        return view(
            'users.index', 
            compact('canCreateUsers', 'error', 'message')
        );
    }
}
