<?php

namespace App\Domain\Model\Documents\Quote;

use App\Infrastructure\Persistence\Repository;
use App\Domain\Service\Documents\BillableDocumentService;
use App\Domain\Constants\Quote\Statuses;
use App\Domain\Model\Documents\Bill\Bill;
use App\Domain\Model\Documents\Shared\AbstractDocumentRepository;
use App\Domain\Model\Documents\Shared\Traits\FillsUserData;

class QuoteRepository extends AbstractDocumentRepository
{
    use FillsUserData;

    protected $repository;
    protected $billableDocumentService;

    public function __construct(
        BillableDocumentService $billableDocumentService
    ) {
        $this->repository = new Repository(Quote::class);
        $this->billableDocumentService = $billableDocumentService;
    }

    /**
     * Get quotes that are marked to be sent.
     */
    public function getMarkedToBeSent()
    {
        return $this->repository->newQuery()
            ->where('status', Statuses::PENDING)
            ->get();
    }

    public function savingNew($quote, &$data, &$protectedData)
    {
        $this->billableDocumentService->createBill($quote, $data);
        $this->billableDocumentService->setBillItems($quote, $data['items']);
    }

    public function updated(&$quote, &$data, &$protectedData)
    {
        $this->billableDocumentService->updateBill($quote, $data);

        if (isset($data['items'])) {
            $this->billableDocumentService->setBillItems($quote, $data['items']);
        }
    }

    public function saved($quote)
    {
        $quote->touch();
    }
}