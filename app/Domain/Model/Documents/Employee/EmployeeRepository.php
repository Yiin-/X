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
                $assignedCountries = $data['assigned_countries'] ?? false;

                /**
                 * If assigned countries array is presented
                 */
                if (is_array($assignedCountries)) {
                    /**
                     * If we have no assigned countries, clean up
                     * user countries.
                     */
                    if (empty($assignedCountries)) {
                        $employeeUser->countries()->sync([]);

                        /**
                         * If assign_all_countries flag is enabled,
                         * save it.
                         */
                        $employeeUser->assign_all_countries = !!($data['assign_all_countries'] ?? false);
                    }
                    /**
                     * Else, if we have some countries to assign, sync them
                     * up and disable assign_all_countries flag.
                     */
                    else {
                        $employeeUser->countries()->sync($assignedCountries);

                        $employeeUser->assign_all_countries = false;
                    }
                }

                /**
                 * Do the same for assigned clients
                 */
                $assignedClients = $data['assigned_clients'] ?? false;

                if (is_array($assignedClients)) {
                    if (empty($assignedClients)) {
                        $employeeUser->clients()->sync([]);

                        $employeeUser->assign_all_clients = !!($data['assign_all_clients'] ?? false);
                    }
                    else {
                        $employeeUser->clients()->sync($assignedClients);

                        $employeeUser->assign_all_clients = false;
                    }
                }

                /**
                 * Sync assigned roles
                 */
                $assignedRoles = $data['assigned_roles'] ?? false;

                if (is_array($assignedRoles)) {
                    $employeeUser->roles()->sync(
                        array_merge($assignedRoles, [
                            // include users private role,
                            // because it's not present in assigned
                            // roles array (cuz it's private i.e. not visible, duh).
                            $employeeUser->role->uuid
                        ])
                    );
                }

                if ($employeeUser->isDirty()) {
                    $employeeUser->save();
                    $employee->touch();
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