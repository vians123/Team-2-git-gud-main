<?php

namespace App\Http\Requests\API\Role;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'permissions' => 'present|array',
        ];
    }

    public function getId(): int
    {
        return (int) $this->route('id');
    }

    public function getName(): string
    {
        return $this->input('name');
    }

    public function getPermissions(): array
    {
        return $this->input('permissions', []);
    }
}
