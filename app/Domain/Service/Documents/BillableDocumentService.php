<?php

namespace App\Domain\Service\Documents;

use App\Domain\Model\Documents\Product\ProductRepository;
use App\Domain\Model\Documents\Credit\CreditRepository;
use App\Domain\Model\Documents\Shared\BillableDocument;
use App\Domain\Model\Documents\Bill\Bill;
use App\Domain\Model\Documents\Bill\BillItem;
use App\Domain\Model\Documents\Quote\Quote;
use App\Domain\Model\Documents\Invoice\Invoice;
use App\Domain\Model\Documents\RecurringInvoice\RecurringInvoice;
use App\Domain\Constants\Pdf\Statuses as PdfStatus;

class BillableDocumentService
{
    public function __construct(
        ProductRepository $productRepository,
        CreditRepository $creditRepository
    ) {
        $this->productRepository = $productRepository;
        $this->creditRepository = $creditRepository;
    }

    public function genPdf(BillableDocument $document, $shouldSave = true)
    {
        $html = $this->genHtml($document);
        $documentName = resource_name($document);

        $filename = \Carbon\Carbon::now()->format('Y-m-d H-i-s') . '.pdf';
        $relativePath = "app/pdfs/{$documentName}/" . $document->uuid;
        $pathToFile = $relativePath . DIRECTORY_SEPARATOR . $filename;

        $absolutePath = storage_path($relativePath);

        list($success, $pdf) = generate_pdf($html, $absolutePath, $filename);

        if (!$success) {
            \Log::error(method_exists($pdf, 'getError') ? $pdf->getError() : $pdf->getMessage());
            return false;
        }
        else {
            if ($shouldSave) {
                return $document->pdfs()->create([
                    'filename' => $filename,
                    'path_to_pdf' => $pathToFile,
                    'status' => PdfStatus::CREATED
                ]);
            }
            else {
                return $pdf;
            }
        }
    }

    public function genHtml(BillableDocument $document)
    {
        if ($document instanceof Invoice) {
            return $this->genInvoiceHtml($document);
        }
        else if ($document instanceof Quote) {
            return $this->genQuoteHtml($document);
        }
        return '';
    }

    public function genInvoiceHtml(Invoice $invoice)
    {
        return view('pdfs.invoice.default.invoice', $this->getInvoicePdfFields($invoice));
    }

    public function genQuoteHtml(Quote $quote)
    {
        return view('pdfs.quote.default.quote', $this->getQuotePdfFields($quote));
    }

    public function getInvoicePdfFields(Invoice $invoice)
    {
        $fields = array_merge($this->getCommonPdfFields($invoice), [
            // Invoice data
            'invoiceNumber' => $invoice->bill->number,
            'poNumber'      => $invoice->bill->po_number,

            // Summary
            'paidIn'        => $invoice->paidIn()
        ]);

        return $fields;
    }

    public function getQuotePdfFields(Quote $quote)
    {
        return array_merge($this->getCommonPdfFields($quote), [
            // Quote data
            'quoteNumber' => $quote->bill->number,
            'poNumber'    => $quote->bill->po_number,
            'dueData'     => $quote->bill->due_date,

            // Summary
            'paidIn'      => $quote->paidIn()
        ]);
    }

    public function getCommonPdfFields(BillableDocument $document)
    {
        return [
            // User data
            'userCompanyName' => $document->company->name,
            'userCompanyEmail' => $document->company->email,

            // Bill data
            'items'          => $document->bill->items,
            'date'           => \Carbon\Carbon::parse($document->bill->date)->format('F j, Y'),

            // Summary
            'subTotal'       => $document->subTotal(),
            'grandTotal'     => $document->amount(['exclude_payments' => true]),
            'discount'       => $document->discount(),
            'tax'            => $document->taxes(),

            // Misc
            'currencySymbol' => $document->bill->currency->symbol,
            'currencyCode'   => $document->bill->currency->code,

            // Texts
            'footerText'     => $document->bill->footer,
            'noteToClient'   => $document->bill->notes,


            'clientName' => $document->client->name,
            'clientAddress1' => $document->client->address1,
            'clientAddress2' => $document->client->address2,
            'clientCountry' => $document->client->country->name,
            'clientEmail' => $document->client->email,
            'userCompanyName' => $document->company->name,
            'userAddress1' => '',
            'userAddress2' => '',
            'userCountry' => '',
            'userCompanyEmail' => $document->company->email
        ];
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
     * Apply credits for billable document EXCEPT if it's quote.
     * In case it's quote, we just create applied credit entries,
     * but do not actually modify the balance of the credits.
     * @param  BillableDocument $document       Document for whom we are applying the credits
     * @param  array            $appliedCredits Array of credit data
     */
    public function applyCredits(BillableDocument $document, array $appliedCredits)
    {
        /**
         * Go though each of the applied credit
         */
        $document->bill->appliedCredits()->whereNotIn('credit_uuid', array_map(function ($appliedCredit) {
            return $appliedCredit['credit_uuid'];
        }, $appliedCredits))->get()->each(function ($appliedCredit) {
            $appliedCredit->credit->balance += convert_currency($appliedCredit->amount, $appliedCredit->currency_code, $appliedCredit->credit->currency_code);
            $appliedCredit->credit->save();
            $appliedCredit->delete();
        });

        foreach ($appliedCredits as $creditToApply) {
            $credit = $this->creditRepository->find($creditToApply['credit_uuid']);

            if (!$credit) {
                continue;
            }

            $currencyCode = $document->bill->currency_code;

            $amountToApplyInBillCurrency = $creditToApply['amount'];
            $amountToApplyInCreditCurrency = convert_currency($amountToApplyInBillCurrency, $currencyCode, $credit->currency_code);

            if (
                !$document->wasRecentlyCreated &&
                $appliedCredit = $document->bill->appliedCredits()->where('credit_uuid', $credit->uuid)->first()
            ) {
                /**
                 * Adjust credit
                 */
                $differenceInBillCurrency = $creditToApply['amount'] - convert_currency($appliedCredit->amount, $appliedCredit->currency_code, $currencyCode);
                $differenceInCreditCurency = convert_currency($differenceInBillCurrency, $currencyCode, $credit->currency_code);

                if (!($document instanceof Quote)) {
                    $credit->balance -= $differenceInCreditCurency;
                    $credit->save();
                }

                $appliedCredit->amount = $creditToApply['amount'];
                $appliedCredit->currency_code = $currencyCode;
                $appliedCredit->save();
            }
            else {
                /**
                 * Create new applied credit
                 */
                if (!($document instanceof Quote)) {
                    $credit->balance -= $amountToApplyInCreditCurrency;
                    $credit->save();
                }

                $document->bill->appliedCredits()->create([
                    'credit_uuid' => $credit->uuid,
                    'amount' => $amountToApplyInBillCurrency,
                    'currency_code' => $currencyCode
                ]);
            }
        }
        $document->bill->load(['appliedCredits']);
        $document->load(['bill']);
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
                'name' => $item['name'],
                'identification_number' => $item['identification_number'],
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
            'name' => 'name',
            'identification_number' => 'identification_number',
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