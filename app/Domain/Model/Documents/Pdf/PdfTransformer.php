<?php

namespace App\Domain\Model\Documents\Pdf;

use League\Fractal;

class PdfTransformer extends Fractal\TransformerAbstract
{
    public function transform(Pdf $pdf)
    {
        return [
            'id' => $pdf->id,

            'filename' => $pdf->filename,
            'status' => $pdf->status,
            'pdfable_type' => class_basename($pdf->pdfable_type),
            'pdfable_id' => $pdf->pdfable_id,

            'created_at' => $pdf->created_at
        ];
    }
}