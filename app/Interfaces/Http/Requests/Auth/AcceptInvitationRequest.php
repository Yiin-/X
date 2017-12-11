<?php

namespace App\Interfaces\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class AcceptInvitationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'data'                  => 'required|array',
            'data.invitation_token' => 'required|exists:users,invitation_token',
            'data.first_name'       => 'required',
            'data.job_title'        => 'required',
            'data.password'         => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'data.first_name' => 'first name',
            'data.job_title' => 'job title',
            'data.password' => 'password'
        ];
    }
}
