<?php

namespace App\Interfaces\Http\Controllers\Documents;

use App\Interfaces\Http\Controllers\DocumentController;
use App\Domain\Model\Documents\Employee\Employee;
use App\Domain\Model\Documents\Employee\EmployeeRepository;
use Illuminate\Validation\Rule;
use App\Interfaces\Http\Requests\Documents\UploadEmployeeProfilePicture;

class EmployeeController extends DocumentController
{
    protected $repository;

    public function __construct(EmployeeRepository $employeeRepository)
    {
        $this->repository = $employeeRepository;
    }

    /**
     * Upload employees profile picture
     */
    public function profilePicture(UploadEmployeeProfilePicture $request, $uuid)
    {
        $employee = Employee::find($uuid);

        $path = implode('/', ['storage', 'uploads', 'employee', $employee->uuid]);
        $filename = 'profile-picture.png';

        $file = $request->file('picture');
        $picture = $file->move(public_path($path), $filename);

        $employee->profile_picture = '/' . implode('/', [$path, $filename]) . '?' . time();
        $employee->save();

        return $employee;
    }

    public function getResourceName()
    {
        return 'employee';
    }

    public function getValidationRules($action)
    {
        switch ($action) {
        case static::VALIDATION_RULES_CREATE:
            return [
                $this->getResourceName() => 'required|array',
                "{$this->getResourceName()}.first_name" => 'required',
                "{$this->getResourceName()}.email" => 'required_if:account,true|email|unique:users,username'
            ];
        case static::VALIDATION_RULES_UPDATE:
            $employee = $this->repository->find(request()->route()->parameter('employee'));

            return [
                $this->getResourceName() => 'required|array',
                "{$this->getResourceName()}.first_name" => 'required',
                "{$this->getResourceName()}.email" => [
                    'required_if:account,true',
                    'email',
                    Rule::unique('users', 'username')->ignore($employee->auth ? $employee->auth->uuid : null, 'uuid')
                ]
            ];
        case static::VALIDATION_RULES_PATCH:
            return $this->getValidationRules('update');
        }

        return [];
    }

    public function getValidationAttributes()
    {
        return [
            "{$this->getResourceName()}.first_name" => 'first name',
            "{$this->getResourceName()}.email" => 'email'
        ];
    }
}