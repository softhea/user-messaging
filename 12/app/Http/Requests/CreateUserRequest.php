<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var User $loggedUser */
        $loggedUser = Auth::user();

        return $loggedUser->getRoleId() <= User::ROLE_ID_ADMIN;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $this->merge(['email_verified_at' => time()]);

        return [
            'username' => 'required|unique:users,username',
            'email' => 'email|unique:users,email',
            'password' => 'required',
            'role_id' => 'required|in:' . implode(',', array_keys(User::ROLES)),
            'email_verified_at' => 'sometimes',
        ];
    }
}
