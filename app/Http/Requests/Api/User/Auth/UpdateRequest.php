<?php

namespace App\Http\Requests\Api\User\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $this->user()->id,
            'phone' => 'nullable|string|unique:users,phone,' . $this->user()->id,
            'image' => 'nullable|image|max:2048',
            'password' => 'nullable|string|confirmed|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'gender' => 'nullable|string|in:male,female',

            'height' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'skin_tones' => 'nullable|string|in:light,fair,medium,tan,dark',
            'body_shape' => 'nullable|string|in:hourglass,pear,apple,rectangle,inverted_triangle',

            'body_measurements' => 'nullable|array',
            'body_measurements.shoulder' => 'nullable|numeric',
            'body_measurements.chest'    => 'nullable|numeric',
            'body_measurements.waist'    => 'nullable|numeric',
            'body_measurements.arm'      => 'nullable|numeric',
            'body_measurements.hip'      => 'nullable|numeric',
            'body_measurements.thigh'    => 'nullable|numeric',
            'body_measurements.leg'      => 'nullable|numeric',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $data = $this->only(['name', 'email', 'phone', 'image', 'password', 'city_id']);
            if (empty(array_filter($data))) {
                $validator->errors()->add('data', __('auth.At least one field must be provided.'));
            }
        });
    }
}
