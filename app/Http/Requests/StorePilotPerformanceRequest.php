<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePilotPerformanceRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Adjust based on your authorization logic
    }

    public function rules()
    {
        return [
            'pilot_id' => 'required|exists:pilots,id',
            'rating' => 'required|integer|min:1|max:5',
        ];
    }
}