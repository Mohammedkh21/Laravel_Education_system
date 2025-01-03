<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules(): array
    {
        return [
            'files.*' => 'required|file|mimes:pdf,doc,docx,ppt,pptx|max:2048',
            'title'=>'string',
            'description'=>'string'
        ];
    }

    function getData()
    {
        return $this->only(['files.*','title','description']);
    }
}
