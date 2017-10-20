<?php

namespace App\Interfaces\Http\Controllers\Web\Traits;

trait ManageSubdomains
{
    public function getSubdomain()
    {
        $scheme = request()->getScheme();
        $host = request()->getHttpHost();

        preg_match('/(?:http[s]*\:\/\/)*(.*?)\.(?=[^\/]*\..{2,5})/i', $host, $matches)[1];

        if (array_key_exists(1, $matches)) {
            return $matches[1];
        }
        else {
            return '';
        }
    }

    public function redirectToUserAccount($user)
    {
        $scheme = request()->getScheme();

        preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', config('app.url'), $regs);
        if ($user->guest_key) {
            $siteAddress = 'demo';
        }
        else {
            $siteAddress = $user->account->site_address;
        }
        return redirect($scheme . '://' . $siteAddress . '.' . $regs['domain'] . request()->getRequestUri());
    }
}