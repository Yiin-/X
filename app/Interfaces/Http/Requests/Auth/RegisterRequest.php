<?php

namespace App\Interfaces\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'company_name'  => 'required',
            'company_email' => 'required|email',
            'site_address'  => 'required|unique:accounts',
            'email'         => 'required|email',
            'password'      => 'required|confirmed',
        ];
    }
}
