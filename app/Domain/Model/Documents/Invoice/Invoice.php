<?php

namespace App\Domain\Model\Documents\Invoice;

use App\Domain\Model\Documents\Bill\Bill;
use App\Domain\Model\Documents\Bill\BillItem;
use App\Domain\Model\Documents\Client\Client;
use App\Domain\Model\Documents\Payment\Payment;
use App\Domain\Model\Documents\Pdf\Pdf;
use App\Domain\Model\Documents\Shared\BillableDocument;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends BillableDocument
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

    /**
     * Paid in amount
     */
    public function paidIn()
    {
        return round(
            $this->payments()->sum('amount')
            + $this->bill->partial
            + $this->bill->appliedCredits->reduce(function ($sum, $appliedCredit) {
                return $sum + convert_currency($appliedCredit->amount, $appliedCredit->currency_code, $this->bill->currency_code);
            }, 0)
        , 2);
    }

    /**
     * Left to pay amount
     */
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

            'client' => [ 'uuid' => $this->client_uuid ],
            'payments' => $this->payments->map(function (Payment $payment) {
                return [ 'uuid' => $payment->uuid ];
            }),

            'pdfs' => $this->pdfs->map(function (Pdf $pdf) {
                return $pdf->transform();
            }),

            'applied_credits' => $this->bill->appliedCredits,

            'amount' => +$amount,
            'paid_in' => +$paid_in,
            'balance' => +($amount - $paid_in),

            'invoice_date' => $this->bill->date,
            'due_date' => $this->bill->due_date,
            'partial' => +$this->bill->partial,
            'currency' => $this->bill->currency,
            'invoice_number' => $this->bill->number,
            'po_number' => $this->bill->po_number,
            'discount_type' => $this->bill->discount_type,
            'discount_value' => +$this->bill->discount,
            'items' => $this->bill->items->map(function (BillItem $item) {
                return $item->transform();
            }),
            'note_to_client' => $this->bill->notes,
            'terms' => $this->bill->terms,
            'footer' => $this->bill->footer,

            'status' => $this->status,

            'is_disabled' => $this->is_disabled,

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
}