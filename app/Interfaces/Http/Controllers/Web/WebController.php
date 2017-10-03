<?php

namespace App\Interfaces\Http\Controllers\Web;

use App\Interfaces\Http\Controllers\AbstractController;
use App\Domain\Model\Authentication\Account\AccountRepository;
use App\Domain\Service\User\AccountService;

class WebController extends AbstractController
{
    private $accountService;
    private $accountRepository;

    public function __construct(AccountService $accountService, AccountRepository $accountRepository)
    {
        $this->accountService = $accountService;
        $this->accountRepository = $accountRepository;
    }

    public function index()
    {
        $data = [];

        if (auth()->check()) {
            $data['preloadedJson'] = json_encode($this->accountService->fetchDataForUser());
        }
        else {
            // $refreshToken = request()->cookie('refreshToken');
            // $auth = $this->authService->attemptRefresh();

            // return $refreshToken;
        }
        return view('front-end.index', $data);
    }

    public function register($account)
    {
        return view('front-end.auth.register', ['account' => $account]);
    }

    public function login($account)
    {
        if (!$this->accountRepository->findBySiteAddress($account)) {
            return redirect('/');
        }
        return view('front-end.auth.login', ['account' => $account]);
    }
}