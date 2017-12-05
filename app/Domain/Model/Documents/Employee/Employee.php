<?php

namespace App\Domain\Model\Documents\Employee;

use App\Domain\Model\Documents\Shared\AbstractDocument;
use App\Domain\Model\Authentication\User\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends AbstractDocument
{
    use SoftDeletes;

    protected $fillable = [
        'user_uuid',
        'first_name',
        'last_name',
        'job_title',
        'email',
        'phone',
        'profile_picture'
    ];

    public function loadRelationships()
    {
        $this->load(['auth', 'auth.roles', 'auth.role']);
    }

    public function getTransformer()
    {
        return new EmployeeTransformer;
    }

    /**
     * User that is linked to this employee
     */
    public function auth()
    {
        return $this->morphOne(User::class, 'authenticable');
    }
}