<?php

namespace App\Http\Requests;

use App\Enums\TradeOfferStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

final class UpdateTradeOfferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'sender_items' => [
                'required',
                'array',
                'min:1',
            ],
            'sender_items.*' => [
                'required',
                'integer',
                Rule::exists('books', 'id')
                    ->withoutTrashed(),
            ],
            'receiver_items' => [
                'required',
                'array',
                'min:1',
            ],
            'receiver_items.*' => [
                'required',
                'integer',
                Rule::exists('books', 'id')
                    ->withoutTrashed(),
            ],
        ];
    }
}
