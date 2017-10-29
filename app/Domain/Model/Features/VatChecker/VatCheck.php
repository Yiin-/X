<?php

namespace App\Domain\Model\Features\VatChecker;

use App\Domain\Model\Documents\Shared\AbstractDocument;

class VatCheck extends AbstractDocument
{
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'user_uuid',
        'name',
        'address',
        'status',
        'country_code',
        'number',
        'message'
    ];

    public function transform()
    {
        return [
            'name' => $this->name,
            'address' => $this->address,
            'status' => $this->status,
            'country_code' => $this->country_code,
            'number' => $this->number,
            'message' => $this->message,

            'created_at' => $this->created_at
        ];
    }
}