<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateNotificationRequest extends FormRequest
{
    public function authorize()
    {
        return true; // or implement your authorization logic
    }

    public function rules()
    {
        return [
            'type' => 'required|in:critical_event,system_update,high_priority_activity',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ];
    }
}
