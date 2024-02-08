<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var User $loggedUser */
        $loggedUser = Auth::user();

        /** @var User $user */
        $user = $this->route('user');

        return  $loggedUser->getRoleId() < $user->getRoleId()
            || Auth::id() === $user->getId();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var User $user */
        $user = $this->route('user');

        if (
            $user->getId() === Auth::id()
            && $user->email !== $this->request->get('email') 
        ) {
            $this->merge(['email_verified_at' => null]);
        }

        return [
            'username' => [
                'required', 
                Rule::unique('users', 'username')->ignore($user->getId()),
            ],
            'email' => [
                'email',
                Rule::unique('users', 'email')->ignore($user->getId()),
            ],
            'password' => 'sometimes',
            'role_id' => 'required|in:' . implode(',', array_keys(User::ROLES)),
            'email_verified_at' => 'sometimes',
        ];
    }
}
