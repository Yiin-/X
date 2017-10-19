<?php

namespace App\Domain\Model\Documents\Shared\Traits;

trait FillsUserData
{
    public function fillUserData(&$protectedData)
    {
        if (!isset($protectedData['user_uuid'])) {
            $protectedData['user_uuid'] = auth()->id();
        }
        $user = app(\App\Domain\Model\Authentication\User\UserRepository::class)->find($protectedData['user_uuid']);

        if (!isset($protectedData['company_uuid'])) {
            // TODO: Pick current selected company, not the first one
            $protectedData['company_uuid'] = $user->companies()->first()->uuid;
        }
    }
}