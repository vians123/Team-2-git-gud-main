<?php

namespace App\Http\Requests\API\Inquiry;

use App\Rules\EmailAddress;
use Illuminate\Foundation\Http\FormRequest;

class CreateInquiryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'fullname' => 'required',
            'email' => ['required', new EmailAddress()],
            'content' => 'required',
        ];
    }

    public function getFullname(): ?string
    {
        return $this->input('fullname', null);
    }

    public function getEmail(): ?string
    {
        return $this->input('email', null);
    }

    public function getInquiryContent(): ?string
    {
        return $this->input('content', null);
    }
}
