<?php

namespace App\Interfaces\Http\Requests\Auth\Role;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'role' => 'required|array',
            'role.name' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'role.name' => 'role\'s name'
        ];
    }
}
