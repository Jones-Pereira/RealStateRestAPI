<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentStoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'property_id' => 'required|exists:properties,id',
            'agent_id' => 'required|exists:agents,id',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i:s',
            'status' => 'required|in:pending,approved,rejected',
        ];
    }
}
