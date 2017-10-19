<?php

namespace App\Interfaces\Http\Controllers\Auth;

use App\Interfaces\Http\Controllers\AbstractController;

class UserController extends AbstractController
{
    public function saveTaskbarState()
    {
        auth()->user()->update([
            'taskbar' => json_encode(request()->get('taskbar'))
        ]);
    }
}