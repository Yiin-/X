<?php

namespace App\Interfaces\Http\Controllers\Documents;

use App\Interfaces\Http\Controllers\AbstractController;
use App\Domain\Constants\Permission\Actions;
use App\Domain\Service\Documents\BillableDocumentService;
use App\Domain\Model\Documents\Pdf\Pdf;
use App\Domain\Model\Documents\Invoice\Invoice;

class StorageController extends AbstractController
{
    public function genHtml($invoiceId, BillableDocumentService $billableDocumentService)
    {
        return $billableDocumentService->genInvoiceHtml(Invoice::where('id', $invoiceId)->first());
    }

    public function pdf($id, $document = null)
    {
        $pdf = Pdf::find($id);

        if (!$pdf) {
            return;
        }
        if (auth()->user()->hasPermissionTo(Actions::VIEW, $pdf->pdfable)) {
            $path = storage_path($pdf->path_to_pdf);

            return response(file_get_contents($path), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'. $pdf->filename .'"'
            ]);
        }
    }
}