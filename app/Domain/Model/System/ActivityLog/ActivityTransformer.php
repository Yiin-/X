<?php

namespace App\Domain\Model\System\ActivityLog;

use League\Fractal;
use App\Domain\Model\System\ActivityLog\ActivityTransformer;

class ActivityTransformer extends Fractal\TransformerAbstract
{
    public function transform(Activity $activity)
    {
        if ($activity->user) {
            $user = $activity->user->transform(['for_backup'])->toArray();
        } else {
            $user = null;
        }

        $documentBackup = [];
        if ($activity->json_backup) {
            $documentBackup = (array) json_decode($activity->json_backup)->documentTransformed ?? [];
        }
        $changes = json_decode($activity->changes, true) ?? [];

        $document = null;

        if ($activity->document_uuid) {
            $document = ($activity->document_type)::find($activity->document_uuid);

            if ($document) {
                $document = [
                    'data' => $document->transform(['for_backup'])->toArray(),
                    'type' => resource_name($document),
                    'from_backup' => false
                ];
            } else {
                $document = [
                    'data' => array_merge(json_decode($documentBackup[0], true), $changes),
                    'type' => resource_name($activity->document_type),
                    'from_backup' => true
                ];
            }
        }

        return [
            'id' => $activity->id,
            'user' => $user,
            'action' => $activity->action,
            'document' => $document,
            'backup' => json_decode($activity->json_backup, true, 4),
            'changes' => $changes,
            'timestamp' => $activity->created_at
        ];
    }
}