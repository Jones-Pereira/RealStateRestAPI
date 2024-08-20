<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PropertyUpdateRequest extends FormRequest
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
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric',
            'address' => 'sometimes|required|string|max:255',
            'city_id' => 'sometimes|required|exists:cities,id',
            'zip_code' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|required|string|max:255',
        ];
    }
}
