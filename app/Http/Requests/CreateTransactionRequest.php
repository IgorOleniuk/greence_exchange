<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTransactionRequest extends FormRequest
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
            'seller_id'          => 'required|integer|exists:users,id',
            'sell_amount'        => 'required|integer',
            'sell_currency'      => 'required|string',
            'receiving_amount'   => 'required|integer',
            'receiving_currency' => 'required|string',
        ];
    }
}
