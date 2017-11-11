<?php

namespace App\Domain\Service\System;

use App\Domain\Model\System\ActivityLog\Activity;
use App\Domain\Model\System\ActivityLog\ActivityTransformer;

class SystemService
{
    public function getAll($user = null)
    {
        return [
            'activityLog' => ($user ?? auth()->user())->activity()
                // ->limit(15)
                ->get()
                ->map(function (Activity $activity) {
                    return (new ActivityTransformer)->transform($activity);
                })
        ];
    }
}