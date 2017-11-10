<?php

namespace App\Domain\Model\Authentication\Account;

use League\Fractal;

class AccountTransformer extends Fractal\TransformerAbstract
{
    public function transform(Account $account)
    {
        return [
            'uuid' => $account->uuid,
            'name' => $account->name,
            'site_address' => $account->site_address
        ];
    }
}