<?php

namespace App\Http\Requests\API;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SearchNotificationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'keyword' => 'nullable',
            'page' => ['nullable', 'numeric'],
            'limit' => ['nullable', 'numeric'],
        ];
    }

    public function getKeyword(): ?string
    {
        return $this->input('keyword', '');
    }

    public function getPage(): int
    {
        return (int) $this->input('page', 1); // page default to 1.
    }

    public function getLimit(): int
    {
        return (int) $this->input('limit', config('search.results_per_page')); // set via config
    }

    public function getOrder(): string
    {
        return $this->input('order', 'desc');
    }

    public function getSort(): string
    {
        return $this->input('sort', 'created_at');
    }

    public function getRecipientId()
    {
        return (int) $this->user()->id;
    }
}
