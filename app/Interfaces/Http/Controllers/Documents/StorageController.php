<?php

namespace App\Interfaces\Http\Controllers\Documents;

use App\Domain\Constants\Permission\Actions;
use App\Domain\Model\Documents\Pdf\Pdf;

class StorageController extends DocumentController
{
    public function pdf(Pdf $pdf)
    {
        if (auth()->user()->hasPermissionTo(Actions::VIEW, $pdf->pdfable)) {
            $path = storage_path($pdf->path_to_pdf);

            return Response::make(file_get_contents($path), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$filename.'"'
            ]);
        }
    }
}