<?php

namespace App\Domain\Model\Documents\Invoice;

use App\Domain\Constants\Bill\DiscountTypes;
use App\Domain\Model\Documents\Bill\Bill;
use App\Domain\Model\Documents\Bill\BillItem;
use App\Domain\Model\Documents\Client\Client;
use App\Domain\Model\Documents\Payment\Payment;
use App\Domain\Model\Documents\Pdf\Pdf;
use App\Domain\Model\Documents\Shared\AbstractDocument;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends AbstractDocument
{
    use SoftDeletes;

    protected $fillable = [
        'client_uuid',
        'status'
    ];

    protected $hidden = [
        'user_uuid',
        'company_uuid'
    ];

    public function pdfs()
    {
        return $this->morphMany(Pdf::class, 'pdfable');
    }

    public function subTotal()
    {
        $amount = 0;

        foreach ($this->bill->items as $item) {
            $amount += $item->cost;
        }

        return $amount;
    }

    public function discount()
    {
        $amount = 0;

        foreach ($this->bill->items as $item) {
            $amount += $item->discount;
        }

        return $amount;
    }

    public function taxes()
    {
        $amount = 0;

        foreach ($this->bill->items as $item) {
            $amount += $item->tax;
        }

        return $amount;
    }

    public function amount()
    {
        $amount = 0;

        foreach ($this->bill->items as $item) {
            $amount += $item->final_price;
        }

        switch ($this->bill->discount_type) {
            case DiscountTypes::FLAT:
                $amount -= $this->bill->discount;
                break;
            case DiscountTypes::PERCENTAGE:
                $amount -= $amount * ($this->bill->discount / 100);
                break;
        }

        return round($amount, 2);
    }

    public function paidIn()
    {
        return round($this->payments()->sum('amount') + $this->bill->partial, 2);
    }

    public function balance()
    {
        return round($this->amount() - $this->paidIn());
    }

    public function transform()
    {
        $amount = $this->amount();
        $paid_in = $this->paidIn();

        return [
            'uuid' => $this->uuid,

            'relationships' => [
                'client' => $this->client_uuid,
                'payments' => $this->payments->map(function (Payment $payment) {
                    return $payment->uuid;
                }),
            ],

            'amount' => $amount,
            'paid_in' => $paid_in,
            'balance' => $amount - $paid_in,

            'invoice_date' => $this->bill->date,
            'due_date' => $this->bill->due_date,
            'partial' => $this->bill->partial,
            'currency_code' => $this->bill->currency_code,
            'invoice_number' => $this->bill->number,
            'po_number' => $this->bill->po_number,
            'discount_type' => $this->bill->discount_type,
            'discount_value' => $this->bill->discount,
            'items' => $this->bill->items->map(function (BillItem $item) {
                return $item->transform();
            }),
            'note_to_client' => $this->bill->notes,
            'terms' => $this->bill->terms,
            'footer' => $this->bill->footer,

            'status' => $this->status,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'archived_at' => $this->archived_at
        ];
    }

    public function invoiceable()
    {
        return $this->morphTo();
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function bill()
    {
        return $this->morphOne(Bill::class, 'billable', null, 'billable_uuid');
    }
}