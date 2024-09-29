<?php

namespace App\Http\Requests\API\Password;

use App\Rules\EmailAddress;
use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => ['required', new EmailAddress()],
        ];
    }

    public function getEmail(): string
    {
        return $this->input('email');
    }
}
