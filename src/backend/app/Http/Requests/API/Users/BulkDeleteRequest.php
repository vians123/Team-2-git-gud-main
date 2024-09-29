<?php

namespace App\Http\Requests\API\Users;

use Illuminate\Foundation\Http\FormRequest;

class BulkDeleteRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'ids' =>  'array|required',
            'ids.*' => 'integer',
        ];
    }

    public function getIds(): array
    {
        return $this->input('ids', []);
    }
}
