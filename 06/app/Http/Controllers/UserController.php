<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class UserController extends Controller
{
    public function create(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $canCreateUsers = $user->getRoleId() <= User::ROLE_ID_ADMIN;
        if (!$canCreateUsers) {
            return redirect()
                ->route('not_allowed')
                ->with('error', 'Not allowed to create users!');
                
        }

        $roles = User::ROLES;
        unset($roles[User::ROLE_ID_SUPERADMIN]);
        foreach ($roles as $roleId => $roleName) {
            if (Auth::user()->role_id > $roleId) {
                unset($roles[$roleId]);
            }
        }

        $defaultRoleId = old('role_id') && isset($roles[old('role_id')]) 
            ? old('role_id')
            : User::ROLE_ID_USER;

        return view(
            'users.create', 
            compact('roles', 'defaultRoleId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'email|unique:users,email',
            'password' => 'required',
            'role_id' => 'required|in:' . implode(',', array_keys(User::ROLES)),
        ]);

        User::query()->create([
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'email_verified_at' => time(),
            'password' => Hash::make($request->input('password')),
            'role_id' => $request->input('role_id'),
        ]);        

        return redirect()
            ->route('users.index')
            ->with('message', 'User created successfuly');
    }

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
        /** @var User $user */
        $user = Auth::user();

        $canCreateUsers = $user->getRoleId() <= User::ROLE_ID_ADMIN;
        $error = Session::has('error') ? Session::get('error') : '';
        $message = Session::has('message') ? Session::get('message') : '';;

        return view(
            'users.index', 
            compact('canCreateUsers', 'error', 'message')
        );
    }

    public function delete(User $user): RedirectResponse
    {
        if (Auth::user()->role_id >= $user->role_id) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Not allowed to delete this user!');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('message', 'User deleted successfully.');
    }
}
