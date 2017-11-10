<?php

namespace App\Domain\Model\Documents\Shared\Traits;

use App\Domain\Model\System\ActivityLog\ActivityRepository;

trait HasHistory
{
    public function getHistory()
    {
        return app(ActivityRepository::class)->getDocumentHistory($this);
    }
}