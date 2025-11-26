<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileUploadRequest extends FormRequest
{
    protected $fileFieldNames;

    public function __construct($fileFieldNames)
    {
        $this->fileFieldNames = $fileFieldNames;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        foreach ($this->fileFieldNames as $fieldName) 
        {
            $rules[$fieldName] = [
                'required',
                'file',
                'max:' . config('medialibrary.max_file_size'),
                'mimetypes:' . implode(',', config('medialibrary.allowed_mime_types')),
                // 'mimes:' . implode(',', config('medialibrary.allowed_extensions')),
            ];
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [];
        foreach ($this->fileFieldNames as $fieldName) 
        {
            $messages[$fieldName . '.mimetypes'] = 'This file type is not allowed';
            $messages[$fieldName . '.mimes'] = 'This file type is not allowed';
        }
        
        return $messages;
    }
}
