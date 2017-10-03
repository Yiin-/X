<?php

namespace App\Interfaces\Http\Controllers\System;

use App\Interfaces\Http\Controllers\AbstractController;
use App\Domain\Model\System\ActivityLog\Activity;
use App\Domain\Model\System\ActivityLog\ActivityTransformer;

class ActivityLogController extends AbstractController
{
    public function index()
    {
        $oldest = request()->get('last');
        $count = request()->get('count');

        return auth()->user()->activity()
            ->when($oldest, function ($query) use ($oldest) {
                return $query->where('id', '<', $oldest);
            })
            ->when($count, function ($query) use ($count) {
                return $query->limit($count);
            })
            ->get()
            ->map(function (Activity $activity) {
                return (new ActivityTransformer)->transform($activity);
            });
    }
}