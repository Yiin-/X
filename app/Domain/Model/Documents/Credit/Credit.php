<?php

namespace App\Domain\Model\Documents\Credit;

use App\Domain\Model\Documents\Shared\Interfaces\BelongsToClient;
use App\Domain\Model\Documents\Client\Client;
use App\Domain\Model\Documents\Passive\Currency;
use App\Domain\Model\Documents\Shared\AbstractDocument;
use Illuminate\Database\Eloquent\SoftDeletes;

class Credit extends AbstractDocument implements BelongsToClient
{
    use SoftDeletes;

    protected $fillable = [
        'client_uuid',
        'amount',
        'currency_code',
        'balance',
        'credit_date',
        'credit_number'
    ];

    protected $hidden = [
        'user_uuid',
        'company_uuid'
    ];

    protected $dates = [
        'credit_date',
        'created_at',
        'updated_at',
        'archived_at',
        'deleted_at'
    ];

    public function getTransformer()
    {
        return new CreditTransformer;
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }

    public function applications()
    {
        return $this->hasMany(AppliedCredit::class);
    }
}