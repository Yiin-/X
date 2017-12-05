<?php

namespace App\Domain\Events\Authentication;

use App\Domain\Model\Authentication\Account\Account;

class AccountWasCreated
{
    public $account;

    public function __construct(Account $account)
    {
        $this->account = $account;
    }
}