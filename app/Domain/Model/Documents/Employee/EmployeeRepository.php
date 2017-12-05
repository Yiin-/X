<?php

namespace App\Domain\Model\Documents\Employee;

use App\Domain\Model\Documents\Shared\AbstractDocumentRepository;
use App\Infrastructure\Persistence\Repository;
use App\Domain\Model\Authorization\Permission\Permission;
use App\Domain\Model\Documents\Shared\Traits\FillsUserData;
use App\Domain\Constants\Permission\Actions as PermissionAction;
use App\Domain\Constants\Permission\Scopes as PermissionScope;

class EmployeeRepository extends AbstractDocumentRepository
{
    use FillsUserData;

    protected $repository;
    protected $accountService;

    public function __construct()
    {
        $this->repository = new Repository(Employee::class);
    }

    public function saved(&$employee, &$data, &$protectedData)
    {
        /**
         * Check if employee should have an account
         */
        if (isset($data['account']) && $data['account']) {
            /**
             * Check if employee already has an account
             */
            if ($employee->auth) {
                /**
                 * Is employees account currently disabled?
                 */
                if ($employee->auth->is_disabled) {
                    $employee->auth->is_disabled = false;
                    $employee->auth->save();
                }

                /**
                 * Do we want to change employees account password?
                 */
                if ($data['password']) {
                    $password = password_hash($data['password'], PASSWORD_BCRYPT);

                    $employee->auth->password = $password;
                    $employee->auth->save();
                }

                $employeeUser = $employee->auth;
            }
            /**
             * In case it doesn't, create new account for employee
             */
            else {
                /**
                 * Do we want to set employees account password ourselves?
                 */
                if ($data['password']) {
                    $password = $data['password'];
                }
                /**
                 * If not, email will be sent for user to choose its password
                 */
                else {
                    $password = null;
                }

                $employeeUser = app(\App\Domain\Service\User\AccountService::class)->createUserForEmployee($employee, $password);
            }

            if ($employeeUser) {
                if (isset($data['assignedClients']) && is_array($data['assignedClients'])) {
                    Permission::ofUser($employeeUser)
                        ->where('scope', PermissionScope::CLIENT)
                        ->whereNotIn('scope_id', $data['assignedClients'])
                        ->where('permissible_type', null)
                        ->delete();

                    foreach ($data['assignedClients'] as $clientUuid) {
                        $ret = app(\App\Domain\Service\Auth\AuthorizationService::class)->givePermissionToUser($employeeUser, [
                            PermissionAction::VIEW,
                            PermissionAction::CREATE,
                            PermissionAction::EDIT,
                            PermissionAction::DELETE
                        ], null, PermissionScope::CLIENT, $clientUuid);
                    }
                }
            }
        } else {
            if ($employee->auth) {
                $employee->auth->is_disabled = true;
                $employee->auth->save();
            }
        }
    }
}