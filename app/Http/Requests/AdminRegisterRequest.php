<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminRegisterRequest extends FormRequest
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
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:8|confirmed',
            'age' => 'required|integer',
            'sex' => 'required|string|in:male,female',
            'phone_number' => 'string|digits_between:7,15',
            'role' => 'string|max:255',
        ];
    }

    public function getData()
    {
        return $this->only(['name', 'email',  'role','password','sex', 'phone_number', 'age']);
    }
}
