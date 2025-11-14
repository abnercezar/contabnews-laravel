<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'sometimes|string|max:120',
            'content' => 'sometimes|string|max:20000',
            'source_url' => 'nullable|url',
            'isSponsoredContent' => 'boolean',
            'is_sponsored' => 'boolean',
        ];
    }
}
