<?php

namespace App\Http\Requests\API\Password;

use App\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'token' => 'required',
            'password' => ['required', new Password(), 'confirmed'],
        ];
    }

    public function getToken(): string
    {
        return $this->input('token');
    }

    public function getPassword(): string
    {
        return $this->input('password');
    }
}
