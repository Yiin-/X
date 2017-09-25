<?php

namespace App\Interfaces\Http\Requests\CRM;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'task' => 'required|array',
            'task.name' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'task.name' => 'task\'s name'
        ];
    }
}
