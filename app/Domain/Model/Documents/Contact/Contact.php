<?php

namespace App\Domain\Model\Documents\Contact;

use App\Domain\Model\Documents\Shared\AbstractDocument;

class Contact extends AbstractDocument
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'website',
        'phone',
        'job_title',
        'contactable_type',
        'contactable_id'
    ];

    protected $touches = [
        'contactable'
    ];

    protected $dispatchesEvents = [];

    public function getTransformer()
    {
        return new ContactTransformer;
    }

    public function contactable()
    {
        return $this->morphTo();
    }
}