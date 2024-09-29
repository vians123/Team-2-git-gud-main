<?php

namespace App\Http\Requests\API;

use App\Rules\Password;
use App\Rules\EmailAddress;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => ['required', new EmailAddress(), 'unique:users,email,' . $this->getId() . ',id'],
            'password' => ['nullable', new Password()],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // limit to 2MB
        ];
    }

    public function getId(): int
    {
        return $this->user() ? (int) $this->user()->id : 0;
    }

    public function getFirstName(): string
    {
        return $this->input('first_name');
    }

    public function getLastName(): string
    {
        return $this->input('last_name');
    }

    public function getEmail(): string
    {
        return $this->input('email');
    }

    public function getPassword(): ?string
    {
        return $this->input('password', null);
    }

    public function getAvatar(): mixed
    {
        return $this->file('avatar', null);
    }

    public function getRole(): string
    {
        $user = $this->user();
        return $user && count($user->roles) > 0 ? $user->roles[0]->name : null;
    }
}
