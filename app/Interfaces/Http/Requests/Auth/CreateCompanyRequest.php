<?php

namespace App\Interfaces\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class CreateCompanyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'company' => 'required|array',
            'company.name' => 'required',
            'company.email' => 'required|nullable'
        ];
    }
}
