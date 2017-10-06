<?php

namespace App\Domain\Model\Documents\Pdf;

use Illuminate\Database\Eloquent\Model;

class Pdf extends Model
{
    protected $fillable = [
        'path_to_pdf',
        'status',
        'pdfable_type',
        'pdfable_uuid'
    ];

    public function pdfable()
    {
        return $this->morphTo();
    }

    public function scopeLatest($query)
    {
        return $this->orderBy('id', 'desc')->first();
    }
}