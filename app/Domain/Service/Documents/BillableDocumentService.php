<?php

namespace App\Domain\Service\Documents;

use App\Domain\Model\Documents\Product\ProductRepository;
use App\Domain\Model\Documents\Shared\BillableDocument;
use App\Domain\Model\Documents\Bill\Bill;
use App\Domain\Model\Documents\Bill\BillItem;
use App\Domain\Model\Documents\Quote\Quote;
use App\Domain\Model\Documents\Invoice\Invoice;
use App\Domain\Model\Documents\RecurringInvoice\RecurringInvoice;

class BillableDocumentService
{
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Create a bill for document with given data.
     * @param  BillableDocument $document Document we're creating bill for.
     * @param  array            $data     Data that we need to create the bill
     * @return Bill                       Created Bill
     */
    public function createBill(BillableDocument $document, array $data)
    {
        $billData = $this->transformDataToBillData($document, $data);

        return Bill::create(array_merge([
            'billable_type' => get_class($document),
            'billable_uuid' => $document->uuid
        ], $billData));
    }

    public function updateBill(BillableDocument $document, array $data)
    {
        $billData = $this->transformDataToBillData($document, $data);

        $document->bill->update($billData);
    }

    /**
     * Transform data to bill data.
     *
     * Basically methods of this kind simply changes
     * key name from 'x_date' to simply 'date'.
     */
    public function transformDataToBillData(BillableDocument $document, array $data)
    {
        $map = false;

        if ($document instanceof Invoice) {
            $map = $this->mapInvoice($data);
        }
        if ($document instanceof Quote) {
            $map = $this->mapQuote($data);
        }
        if ($document instanceof RecurringInvoice) {
            $map = $this->mapRecurringInvoice($data);
        }

        if (!$map) {
            return $data;
        }

        $billData = [];

        foreach ($map as $field => $billField) {
            if (array_key_exists($field, $data)) {
                $billData[$billField] = $data[$field];
            } else if(array_key_exists($billField, $data)) {
                $billData[$billField] = $data[$billField];
            }
        }
        return $billData;
    }

    public function mapInvoice(array &$data)
    {
        return [
            'invoice_number' => 'number',
            'po_number' => 'po_number',
            'partial' => 'partial',
            'discount_value' => 'discount',
            'discount_type' => 'discount_type',
            'invoice_date' => 'date',
            'due_date' => 'due_date',
            'currency_code' => 'currency_code',
            'note_to_client' => 'notes',
            'terms' => 'terms',
            'footer' => 'footer'
        ];
    }

    public function mapQuote(array &$data)
    {
        return [
            'quote_number' => 'number',
            'po_number' => 'po_number',
            'partial' => 'partial',
            'discount' => 'discount',
            'discount_type' => 'discount_type',
            'currency_code' => 'currency_code',
            'quote_date' => 'date',
            'due_date' => 'due_date',
            'notes' => 'notes',
            'terms' => 'terms',
            'footer' => 'footer'
        ];
    }

    public function mapRecurringInvoice(array &$data)
    {
        return [
            'po_number' => 'po_number',
            'discount_value' => 'discount',
            'discount_type' => 'discount_type',
            'currency_code' => 'currency_code',
            'note_to_client' => 'notes',
            'terms' => 'terms',
            'footer' => 'footer'
        ];
    }

    /**
     * Delete old and create new items for billable document
     * @param BillableDocument $document Document for whom we are creating new items
     * @param array            $items    Array of items
     */
    public function setBillItems(BillableDocument $document, array $items)
    {
        // All items should have reference to the product.
        $this->createMissingProducts($document, $items);

        // because we're assigning new items to the document, delete old items first
        $document->bill->items()->delete();
        $document->bill->items()->createMany(collect($items)->map([$this, 'transformToBillItemData'])->toArray());
    }

    /**
     * If item has no assigned item, delete it.
     * @param  BillableDocument $document We will use currency_code from this document for products.
     * @param  array            &$items   Array of given items
     * @return void
     */
    protected function createMissingProducts(BillableDocument $document, array &$items)
    {
        // Find existing products
        $existingProducts = $this->productRepository->newQuery()
            ->whereIn('uuid', collect($items)->pluck('product_uuid'))
            ->get()->pluck('uuid')->toArray();

        // Loop through given items list, and check if it's product id is accessible to us
        foreach ($items as &$item) {
            if (in_array($item['product_uuid'], $existingProducts)) {
                continue;
            }
            // If not, assign id of newly created product to the item
            $item['product_uuid'] = $this->productRepository->create([
                'name' => $item['product_name'],
                'price' => $item['cost'],
                'qty' => $item['qty'],
                'discount' => $item['discount'],
                'tax_rate_uuid' => $item['tax_rate_uuid'],
                'currency_code' => $document->bill->currency_code
            ])->uuid;
        }
    }

    /**
     * Transform item data we got from request to
     * data we can save to database.
     */
    public function transformToBillItemData($item, $index)
    {
        $billItemData = [];

        foreach ([
            'product_uuid' => 'product_uuid',
            'name' => 'product_name',
            'cost' => 'cost',
            'qty' => 'qty',
            'discount' => 'discount',
            'tax_rate_uuid' => 'tax_rate_uuid'
        ] as $billItemField => $itemField) {
            if (array_key_exists($itemField, $item)) {
                $billItemData[$billItemField] = $item[$itemField];
            } else if (array_key_exists($billItemField, $item)) {
                $billItemData[$billItemField] = $item[$billItemField];
            }
        }
        $billItemData['index'] = $index;

        return $billItemData;
    }
}