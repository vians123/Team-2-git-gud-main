<?php

namespace App\Http\Requests\API\Token;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class VerifyTokenRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'type' => [
                'required',
                Rule::in(['activation', 'password_reset'])
            ],
            'token' => 'required',
        ];
    }

    public function getType(): ?string
    {
        return $this->input('type', null);
    }

    public function getToken(): ?string
    {
        return $this->input('token', null);
    }
}
