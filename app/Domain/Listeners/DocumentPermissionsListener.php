<?php

namespace App\Domain\Listeners;

use App\Domain\Constants\Permission\Actions as PermissionActions;
use App\Domain\Events\Document\DocumentIsDeleting;
use App\Domain\Events\Document\DocumentWasCreated;
use App\Domain\Model\Authentication\Company\Company;
use App\Domain\Model\Authorization\Role\Role;

class DocumentPermissionsListener
{
    /**
     * Create permissions for company role to manage given document.
     */
    public function createPermissions($event)
    {
        \Log::debug('creating document permissions');
        /**
         * @var Company $company
         */
        $company = $event->document->company;

        \Log::debug($event->document);

        if (!$company) {
            \Log::debug('no company');
            \Log::debug($event->document->company_uuid);
            \Log::debug($event->document->company);
            \Log::debug($event->document->company()->first());
            return;
        }

        /**
         * @var Role $role
         */
        $role = $company->roles()->whereNull('parent_role_uuid')->first();

        if (!$role) {
            \Log::debug('no role');
            return;
        }

        foreach (PermissionActions::LIST_DOCUMENT_ACTIONS as $action) {
            $permission = $event->document->permissions()->create([
                'type' => $action
            ]);
            $role->permissions()->attach($permission->id);
        }
    }

    /**
     * Delete document permissions if document is being force deleted.
     * @param $event
     */
    public function deletePermissions($event)
    {
        \Log::debug('deleting permissions');
        if ($event->document->isForceDeleting()) {
            $event->document->permissions()->delete();
        }
    }

    public function subscribe($events)
    {
        $events->listen([
                DocumentWasCreated::class,
            ],
            self::class . '@createPermissions'
        );

        $events->listen([
                DocumentIsDeleting::class,
            ],
            self::class . '@deletePermissions'
        );
    }
}