<?php

namespace App\Domain\Model\Documents\Client;

use App\Domain\Model\Documents\Profile\Profile;
use App\Domain\Model\Documents\Shared\AbstractDocument;

class ClientContact extends AbstractDocument
{
    protected $touches = [
        'client'
    ];

    public function getTransformer()
    {
        return new ClientContactTransformer;
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}