<?php

namespace App\Interfaces\Http\Requests\Documents;

use Illuminate\Foundation\Http\FormRequest;

class TabularDataRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'p' => 'required',
            'a' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'p' => 'page',
            'a' => 'items per page'
        ];
    }
}
