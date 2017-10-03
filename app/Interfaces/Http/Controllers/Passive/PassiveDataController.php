<?php

namespace App\Interfaces\Http\Controllers\Passive;

use App\Domain\Service\Passive\PassiveDataService;

class PassiveDataController
{
    protected $passiveDataService;

    public function __construct(PassiveDataService $passiveDataService)
    {
        $this->passiveDataService = $passiveDataService;
    }

    public function all()
    {
        return $this->passiveDataService->getAll();
    }
}