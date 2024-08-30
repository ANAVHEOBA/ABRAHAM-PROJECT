<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignPilotRequest extends FormRequest
{
    public function rules()
    {
        return [
            'order_id' => 'required|exists:orders,id',
        ];
    }
}