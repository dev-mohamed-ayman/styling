<?php

namespace App\Http\Requests\Api\User\Address;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'lat' => 'required_with:lng|numeric|between:-90,90',
            'lng' => 'required_with:lat|numeric|between:-180,180',
            'area_id' => 'required|exists:areas,id',
            'notes' => 'nullable|string|max:255',
            'is_primary' => 'nullable|boolean',
        ];
    }
}
