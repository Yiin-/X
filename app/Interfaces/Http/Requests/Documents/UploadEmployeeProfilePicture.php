<?php

namespace App\Interfaces\Http\Requests\Documents;

use Illuminate\Foundation\Http\FormRequest;

class UploadEmployeeProfilePicture extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'picture' => 'required|image'
        ];
    }
}
