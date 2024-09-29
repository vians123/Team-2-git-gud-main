<?php

namespace App\Http\Requests\API\Users;

use App\Rules\Password;
use App\Rules\EmailAddress;
use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => ['required', new EmailAddress(), 'unique:users,email'],
        ];
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
}
