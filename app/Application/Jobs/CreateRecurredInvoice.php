<?php

namespace App\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Domain\Constants\Invoice\Statuses;

class CreateRecurredInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $recurringInvoice;

    public function __construct(RecurringInvoice $recurringInvoice)
    {
        $this->recurringInvoice = $recurringInvoice;
    }

    public function handle(InvoiceRepository $invoiceRepository)
    {
        $invoice = $invoiceRepository->createRaw([
            'status' => Statuses::PENDING
        ]);

        $billData = $this->recurringInvoice->bill->replicate([
            // except
            'date', 'due_date', 'billable_type', 'billable_uuid'
        ]);

        $today = Carbon::now();

        /**
         * Set invoice date to current date
         */
        $billData['date'] = $today->toDateTimeString();

        /**
         * Set invoice due date
         */
        switch ($this->recurringInvoice->frequency_type) {
            /**
             * Weekday
             * 1 to 28 []
             *
             * 1 = First sunday after today
             * 7 = First saturday after today
             * 8 = Second sunday after today
             * ...
             *
             * {nth} {dayOfWeek} after today
             */
            case 'week':
                $nth = ceil($this->recurringInvoice->frequency_value / 7);
                $dayOfWeek = ceil(($this->recurringInvoice->frequency_value - 1) / 7);

                $dueDate = $today->copy();

                do {
                    $dueDate->next($dayOfWeek);
                }
                while (--$nth > 0);

                $billData['due_date'] = $dueDate->toDateString();
                break;

            /**
             * Day of month
             * 1 to 31 []
             */
            case 'month':
            case 'year':
                $dayOfMonth = $this->recurringInvoice->frequency_value;
                $dueDate = $today->copy();

                if ($dueDate->daysInMonth < $dayOfMonth) {
                    $dayOfMonth = $dueDate->daysInMonth;
                }
                $dueDate->day = $dayOfMonth;

                while ($dueDate->isToday() || $dueDate->isPast()) {
                    $dueDate->addMonth();
                }

                $billData['due_date'] = $dueDate->toDateString();
                break;
        }

        /**
         * Copy bill and bill items
         */
        $bill = $this->billableDocumentService->createBill($invoice, $billData);
        $items = $bill->items->map(function ($item) {
            return $item->replicate([
                'bill_id'
            ])->toArray();
        });
        $bill->items()->createMany($items);

        $this->recurringInvoice->update([
            'last_sent_at' => $today
        ]);

        \Log::info('Sending recurring invoice...');
    }
}