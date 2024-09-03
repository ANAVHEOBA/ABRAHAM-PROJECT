<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
        ];
    }
}

class UpdateTicketRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'status' => 'sometimes|required|in:open,in_progress,resolved,closed',
            'priority' => 'sometimes|required|in:low,medium,high',
            'assigned_to' => 'sometimes|required|exists:users,id',
        ];
    }
}

class StoreCommentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'content' => 'required|string',
            'ticket_id' => 'required|exists:tickets,id',
        ];
    }
}