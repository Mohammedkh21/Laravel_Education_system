<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ComunicationStoreRequest extends FormRequest
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
        $rules =  [
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:url,number',
            'content' =>  ['required', 'string'],
        ];

        if (request('type') === 'url') {
            $rules['content'][] = 'url';
        } elseif (request('type') === 'number') {
            $rules['content'][] = 'digits_between:7,15';
        }
        $rules['content'][] = Rule::unique('communications')
            ->where('name', request('name'));

        return  $rules;
    }

    public function getData()
    {
        return $this->only(['name', 'type','content']);
    }
}
