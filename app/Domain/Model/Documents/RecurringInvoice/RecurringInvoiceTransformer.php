<?php

namespace App\Domain\Model\Documents\RecurringInvoice;

use League\Fractal;

class RecurringInvoiceTransformer extends Fractal\TransformerAbstract
{
    public function transform(RecurringInvoice $recurringInvoice)
    {
        return [
            'uuid' => $recurringInvoice->uuid,
            'company_uuid' => $recurringInvoice->company_uuid,

            'is_disabled' => $recurringInvoice->is_disabled,

            'created_at' => $recurringInvoice->created_at,
            'updated_at' => $recurringInvoice->updated_at,
            'archived_at' => $recurringInvoice->archived_at,
            'deleted_at' => $recurringInvoice->deleted_at
        ];
    }
}