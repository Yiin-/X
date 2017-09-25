<?php

// User's Private Channel
Broadcast::channel('user:{account}.{uuid}', function ($user, $account, $uuid) {
    return $user->uuid === $uuid && $user->account->uuid === $account;
});