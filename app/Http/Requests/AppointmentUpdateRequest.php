<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentUpdateRequest extends FormRequest
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
            'user_id' => 'sometimes|required|exists:users,id',
            'property_id' => 'sometimes|required|exists:properties,id',
            'agent_id' => 'sometimes|required|exists:agents,id',
            'date' => 'sometimes|required|date',
            'time' => 'sometimes|required|date_format:H:i:s',
            'status' => 'sometimes|required|in:pending,approved,rejected',
        ];
    }
}
