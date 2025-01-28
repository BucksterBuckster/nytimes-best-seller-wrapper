<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BestSellersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'author' => 'sometimes|string',
            'isbn' => 'sometimes|array',
            'isbn.*' => 'string',
            'title' => 'sometimes|string',
            'offset' => 'sometimes|integer|min:0',
        ];
    }
}
