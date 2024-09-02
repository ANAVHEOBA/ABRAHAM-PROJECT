<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Transaction;

class StoreTransactionRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Adjust based on your authorization logic
    }

    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'pilot_id' => 'required_if:type,payout|exists:pilots,id',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:' . implode(',', [
                Transaction::TYPE_PAYMENT,
                Transaction::TYPE_REFUND,
                Transaction::TYPE_PAYOUT
            ]),
            'description' => 'required|string|max:255',
        ];
    }
}