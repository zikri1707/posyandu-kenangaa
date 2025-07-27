<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|string|in:published,draft',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Judul artikel wajib diisi.',
            'content.required' => 'Konten artikel wajib diisi.',
        ];
    }
}
