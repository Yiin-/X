<?php

namespace App\Interfaces\Http\Controllers\Auth;

use App\Interfaces\Http\Controllers\AbstractController;
use App\Interfaces\Http\Requests\Auth\Role\StoreRoleRequest;
use App\Interfaces\Http\Requests\Auth\Role\UpdateRoleRequest;
use App\Domain\Model\Authorization\Role\Role;
use App\Domain\Model\Authorization\Role\RoleRepository;

class RoleController extends AbstractController
{
    protected $repository;

    public function __construct(RoleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function store(StoreRoleRequest $request)
    {
        $this->authorize('create', Role::class);

        return $this->repository->create($request->get('role'))->transform()->toArray();
    }

    public function update(UpdateRoleRequest $request, $uuid)
    {
        $this->authorize('update', $this->repository->find($uuid));

        $data = $request->get('role', []);

        $data['uuid'] = $uuid;

        return $this->repository->update($data)->transform()->toArray();
    }

    public function destroy($uuid)
    {
        $this->authorize('delete', $this->repository->find($uuid));

        return $this->repository->delete($uuid)->transform()->toArray();
    }
}