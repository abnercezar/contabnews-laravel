<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClassifiedRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'body' => ['nullable', 'string', 'max:5000'],
            'source_url' => ['nullable', 'url', 'max:2000'],
            'is_sponsored' => ['nullable', 'boolean'],
        ];
    }
}
