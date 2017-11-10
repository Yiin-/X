<?php

namespace App\Domain\Model\Documents\Pdf;

use App\Domain\Model\Documents\Shared\AbstractDocument;
use App\Domain\Events\Document\PdfWasCreated;

class Pdf extends AbstractDocument
{
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'filename',
        'path_to_pdf',
        'status',
        'pdfable_type',
        'pdfable_id'
    ];

    protected $dispatchesEvents = [
        'created' => PdfWasCreated::class
    ];

    public function getTransformer()
    {
        return new PdfTransformer;
    }

    public function pdfable()
    {
        return $this->morphTo();
    }

    public function scopeLatest($query)
    {
        return $this->orderBy('id', 'desc')->first();
    }
}