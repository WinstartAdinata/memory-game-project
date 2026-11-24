<?php

namespace App\Http\Requests\Game;

use Illuminate\Foundation\Http\FormRequest;

class StoreGameRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'difficulty' => ['required', 'in:easy,hard'],
        ];
    }

    public function messages(): array
    {
        return [
            'difficulty.required' => 'Please select a difficulty.',
            'difficulty.in' => 'The selected difficulty is invalid.',
        ];
    }
}
