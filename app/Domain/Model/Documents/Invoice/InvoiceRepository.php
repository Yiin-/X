<?php

namespace App\Domain\Model\Documents\Invoice;

use App\Infrastructure\Persistence\Repository;
use App\Domain\Service\Documents\BillableDocumentService;
use App\Domain\Constants\Invoice\Statuses;
use App\Domain\Model\Documents\Bill\Bill;
use App\Domain\Model\Documents\Quote\Quote;
use App\Domain\Model\Documents\Shared\AbstractDocumentRepository;
use App\Domain\Model\Documents\Shared\Traits\FillsUserData;

class InvoiceRepository extends AbstractDocumentRepository
{
    use FillsUserData;

    protected $repository;
    protected $billableDocumentService;

    public function __construct(
        BillableDocumentService $billableDocumentService
    ) {
        $this->repository = new Repository(Invoice::class);
        $this->billableDocumentService = $billableDocumentService;
    }

    /**
     * Get invoices that are marked to be sent.
     */
    public function getMarkedToBeSent()
    {
        return $this->repository->newQuery()
            ->where('status', Statuses::PENDING)
            ->get();
    }

    /**
     * Fill missing data before creating invoice
     */
    public function fillMissingData(&$data, &$protectedData)
    {
        /**
         * If this invoice was converted from quote,
         * link it to the quote.
         */
        if (isset($data['quote_uuid'])) {
            $protectedData['invoiceable_type'] = Quote::class;
            $protectedData['invoiceable_uuid'] = $data['quote_uuid'];
        }
    }

    public function savingNew($invoice, &$data, &$protectedData)
    {
        $this->billableDocumentService->createBill($invoice, $data);
        $this->billableDocumentService->setBillItems($invoice, $data['items']);
        $this->billableDocumentService->applyCredits($invoice, $data['applied_credits']);
    }

    public function updated(&$invoice, &$data, &$protectedData)
    {
        $this->billableDocumentService->updateBill($invoice, $data);

        if (isset($data['items'])) {
            $this->billableDocumentService->setBillItems($invoice, $data['items']);
        }
        if (isset($data['applied_credits'])) {
            $this->billableDocumentService->applyCredits($invoice, $data['applied_credits']);
        }
    }

    public function saved($invoice)
    {
        $invoice->touch();
    }
}