<?php

namespace App\Interfaces\Http\Controllers\Web;

use App\Interfaces\Http\Controllers\AbstractController;
use App\Interfaces\Http\Controllers\Web\Traits\ManageSubdomains;

class LandingPageController extends AbstractController
{
    use ManageSubdomains;

    public function index()
    {
        return view('front-end.landing-page.index');
    }
}