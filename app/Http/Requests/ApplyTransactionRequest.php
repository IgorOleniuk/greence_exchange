<?php

namespace App\Http\Requests;

use App\Rules\CheckBuyerAbilityCompleteTransaction;
use Illuminate\Foundation\Http\FormRequest;

class ApplyTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'transaction_id'    => 'required|integer|exists:transactions,id',
            'buyer_id'          => ['required', 'integer', 'exists:users,id', new CheckBuyerAbilityCompleteTransaction]
        ];
    }
}
