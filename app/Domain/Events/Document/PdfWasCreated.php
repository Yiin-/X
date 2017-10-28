<?php

namespace App\Domain\Events\Document;

use App\Domain\Model\Authentication\User\User;
use App\Domain\Events\Shared\BroadcastableEvent;
use App\Domain\Constants\Permission\Actions as PermissionActions;
use App\Domain\Model\Documents\Pdf\Pdf;

class PdfWasCreated
{
    public $pdf;

    public function __construct(Pdf $pdf)
    {
        $this->pdf = $pdf;

        \Log::info('Pdf #'.$pdf->id.' was created: ' . $pdf->filename);

        $users = User::withPermissionTo(PermissionActions::VIEW, $pdf->pdfable)->get();

        foreach ($users as $user) {
            broadcast(new BroadcastableEvent(static::class, $user, $pdf));
        }
    }
}