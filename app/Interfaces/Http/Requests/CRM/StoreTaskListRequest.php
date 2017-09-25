<?php

namespace App\Interfaces\Http\Requests\CRM;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskListRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'task-list' => 'required|array',
            'task-list.name' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'task-list.name' => 'task\'s name'
        ];
    }
}
