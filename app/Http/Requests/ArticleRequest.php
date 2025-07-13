<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|unique:articles,title,'.$this->article?->id,
            'content' => 'required|string',
            'thumbnail' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'sometimes|in:draft,published,archived',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:30',
            'meta_description' => 'nullable|string|max:160',
            'is_featured' => 'sometimes|boolean'
        ];
    }

    public function prepareForValidation()
    {
        if ($this->isMethod('POST') && !$this->has('status')) {
            $this->merge(['status' => 'draft']);
        }
    }
}