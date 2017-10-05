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
        /**
         * @var Company $company
         */
        $company = $event->document->company;

        if (!$company) {
            return;
        }

        /**
         * @var Role $role
         */
        $role = $company->roles()->whereNull('parent_role_uuid')->first();

        if (!$role) {
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