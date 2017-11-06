<?php

namespace App\Domain\Model\Documents\RecurringInvoice;

use App\Infrastructure\Persistence\Repository;
use App\Domain\Service\Documents\BillableDocumentService;
use App\Domain\Model\Documents\Bill\Bill;
use App\Domain\Model\Documents\Shared\AbstractDocumentRepository;
use App\Domain\Model\Documents\Shared\Traits\FillsUserData;
use App\Domain\Constants\RecurringInvoice\Statuses;
use Carbon\Carbon;

class RecurringInvoiceRepository extends AbstractDocumentRepository
{
    use FillsUserData;

    protected $repository;
    protected $billableDocumentService;

    public function __construct(BillableDocumentService $billableDocumentService)
    {
        $this->repository = new Repository(RecurringInvoice::class);
        $this->billableDocumentService = $billableDocumentService;
    }

    public function getActiveAndReadyToBeSent()
    {
        $active = [];

        $today = today();

        $recurringInvoices = $this->repository->newQuery()
            ->where('status', Statuses::ACTIVE)
            ->where('start_date', '<=', $today)
            ->where(function ($query) use ($today) {
                $query->whereNull('end_date')->orWhere('end_date', '>=', $today);
            })
            ->get();

        /**
         * Loop through each of active recurring invoice
         */
        foreach ($recurringInvoices as $recurringInvoice) {
            /**
             * Try to get either start_date or last date recurring invoice was sent
             */
            try {
                if ($recurringInvoice->last_sent_at) {
                    $date = Carbon::parse($recurringInvoice->last_sent_at);
                } else {
                    // $date = Carbon::parse($recurringInvoice->bill->date);
                    $active [] = $recurringInvoice;
                    continue;
                }
            }
            catch (\InvalidArgumentException $e) {
                // Skip this recurring invoice, if we couldn't get the starting date
                continue;
            }

            /**
             * Add interval period to the starting date and if it's today, we can
             * safely assume that invoice should be sent today.
             */
            switch ($recurringInvoice->frequency_type) {
                case 'week':
                    $date->addWeeks($recurringInvoice->frequency_value);
                    break;
                case 'month':
                    $date->addMonths($recurringInvoice->frequency_value);
                    break;
                case 'year':
                    $date->addYears($recurringInvoice->frequency_value);
                    break;
            }

            if ($date->isToday()) {
                $active [] = $recurringInvoice;
            }
        }
        return $active;
    }

    public function adjustData(&$data)
    {
        if (array_key_exists('frequency_type', $data) && is_null($data['frequency_type'])) {
            unset($data['frequency_type']);
        }
        if (array_key_exists('frequency_id', $data) && !$data['frequency_id']) {
            unset($data['frequency_id']);
        }
    }

    public function saving(&$recurringInvoice, &$data, &$protectedData)
    {
        $this->billableDocumentService->createBill($recurringInvoice, $data);
        $this->billableDocumentService->setBillItems($recurringInvoice, $data['items']);
    }

    public function updated(&$recurringInvoice, &$data, &$protectedData)
    {
        $this->billableDocumentService->updateBill($recurringInvoice, $data);

        if (isset($data['items'])) {
            $this->billableDocumentService->setBillItems($recurringInvoice, $data['items']);
        }
    }

    public function saved($recurringInvoice)
    {
        $recurringInvoice->touch();
    }
}