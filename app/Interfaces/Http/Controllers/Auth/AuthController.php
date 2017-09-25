<?php

namespace App\Interfaces\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Interfaces\Http\Controllers\AbstractController;
use App\Domain\Service\Auth\AuthService;
use App\Domain\Service\User\AccountService;
use App\Interfaces\Http\Requests\Auth\RegisterRequest;
use App\Interfaces\Http\Requests\Auth\LoginRequest;

class AuthController extends AbstractController
{
    private $authService;
    private $accountService;

    public function __construct(
        AuthService $authService,
        AccountService $accountService
    ) {
        $this->authService = $authService;
        $this->accountService = $accountService;
    }

    public function register(RegisterRequest $request)
    {
        /**
         * Name of the company, which also will be used as default account name.
         * @var string
         */
        $companyName = $request->get('company_name');

        /**
         * Company email
         * @var string
         */
        $companyEmail = $request->get('company_email');
        $siteAddress = $request->get('site_address');

        /**
         * User details
         */
        $firstName = $request->get('first_name');
        $lastName = $request->get('last_name');
        $userEmail = $request->get('email');
        $userPassword = $request->get('password');

        $data = $this->authService->register($companyName, $companyEmail, $siteAddress, $firstName, $lastName, $userEmail, $userPassword);

        return response()->json($data);
    }

    public function login(LoginRequest $request)
    {
        $siteAddress = $request->get('site_address');
        $username = $request->get('username');
        $password = $request->get('password');

        $data = $this->authService->attemptLogin($siteAddress, $username, $password);

        return response()->json($data);
    }

    public function refresh(Request $request)
    {
        return response()->json($this->authService->attemptRefresh());
    }

    public function logout()
    {
        $this->authService->logout();

        return response()->json(null, 204);
    }
}