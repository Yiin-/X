<?php

namespace App\Domain\Model\Documents\Shared\Traits;

trait FillsUserData
{
    public function fillUserData(&$data, &$protectedData)
    {
        if (!isset($protectedData['user_uuid'])) {
            $protectedData['user_uuid'] = auth()->id();
        }
        $user = app(\App\Domain\Model\Authentication\User\UserRepository::class)->find($protectedData['user_uuid']);

        if (!isset($protectedData['company_uuid'])) {
            if (isset($data['company_uuid'])) {
                $protectedData['company_uuid'] = $data['company_uuid'];
            }
        }
    }
}