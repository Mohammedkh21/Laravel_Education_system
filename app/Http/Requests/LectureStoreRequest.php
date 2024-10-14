<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LectureStoreRequest extends FormRequest
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
            'title'=>'required|string',
            'description'=>'required|string',
            'content'=>'required',
            'files.*' => 'sometimes|required|file|mimes:pdf,doc,docx,ppt,pptx|max:2048',
        ];
    }

    function getData()
    {
        return $this->only(['title','description','content','files.*']);
    }
}
