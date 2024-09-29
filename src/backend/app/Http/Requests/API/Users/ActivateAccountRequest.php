<?php

namespace App\Http\Requests\API\Users;

use App\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class ActivateAccountRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'token' => 'required',
            'password' => [new Password(), 'confirmed'],
        ];
    }

    public function getToken(): string
    {
        return $this->input('token');
    }

    public function getPassword(): ?string
    {
        return $this->input('password', null);
    }
}
