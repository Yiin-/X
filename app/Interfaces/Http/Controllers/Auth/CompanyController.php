<?php

namespace App\Interfaces\Http\Controllers\Auth;

use App\Interfaces\Http\Controllers\AbstractController;
use App\Interfaces\Http\Requests\Auth\CreateCompanyRequest;
use App\Domain\Model\Authentication\Company\CompanyRepository;

class CompanyController extends AbstractController
{
    protected $companyRepository;

    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function store(CreateCompanyRequest $request)
    {
        return $this->companyRepository->create($request->get('company'))->transform()->toArray();
    }
}