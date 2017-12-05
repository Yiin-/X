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

    /**
     * User state is trivial but handy information, like:
     *     currently selected company;
     *     selected table options e.g. visible rows/pag;
     *     dashboard filters;
     *     etc
     *
     * We save it so it's consistant across different devices.
     * Saving as json because it's unlikely we'll ever use
     * that data for any meaningful processing.
     *
     * Note:
     * It sort of sucks not to have any idea what exactly is stored
     * in the state though, so better implementation would
     * be quite helpful.
     */
    public function saveState()
    {
        auth()->user()->update([
            'state' => json_encode(request()->get('state'))
        ]);
    }

    public function saveStateCompany($uuid)
    {
        auth()->user()->update([
            'state' => array_merge(
                json_decode(auth()->user()->state) ?? [],
                [ 'company_uuid' => $uuid ]
            )
        ]);
    }
}