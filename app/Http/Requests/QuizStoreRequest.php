<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuizStoreRequest extends FormRequest
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
            'title' => 'required|string',
            'description'=> 'required|string',
            'degree' => 'required|integer',
            'visibility'=> 'required|boolean',
            'start_in' => 'required|date|after:now',
            'end_in' => 'required|date|after:start_in',
        ];
    }
    function getData()
    {
        return $this->only(['title','description','degree','visibility','start_in','end_in']);
    }
}