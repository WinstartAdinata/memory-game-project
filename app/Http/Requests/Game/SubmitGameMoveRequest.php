<?php

namespace App\Http\Requests\Game;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SubmitGameMoveRequest extends FormRequest
{
    /**
     *
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'cards.required' => 'Cards must be selected.',
            'cards.array' => 'Invalid card selection.',
            'cards.min' => 'Not enough cards selected.',
            'cards.max' => 'Too many cards selected.',
            'cards.*.integer' => 'Invalid card ID.',
            'cards.*.distinct' => 'A card cannot be selected twice.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cards' => ['required', 'array', 'min:2', 'max:3'],
            'cards.*' => [
                'required',
                'integer',
                'distinct',
            ],
        ];
    }
}
