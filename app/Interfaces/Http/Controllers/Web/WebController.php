<?php

namespace App\Interfaces\Http\Controllers\Web;

use App\Interfaces\Http\Controllers\AbstractController;
use App\Domain\Model\Authentication\Account\AccountRepository;
use App\Domain\Service\User\AccountService;
use App\Domain\Service\Auth\AuthService;

class WebController extends AbstractController
{
    private $accountService;
    private $accountRepository;
    private $authService;

    public function __construct(AccountService $accountService, AccountRepository $accountRepository, AuthService $authService)
    {
        $this->accountService = $accountService;
        $this->accountRepository = $accountRepository;
        $this->authService = $authService;
    }

    public function serveApplication()
    {
        $data = [
            'preloadedJson' => []
        ];

        // If user is authenticated
        if (auth()->check()) {
            $user = auth()->user();
            // Logout it out, if that's a guest account
            if ($user->guest_key) {
                // Revoke issued access token
                $this->authService->logout();
                // Also delete guest account, because there is no way for
                // user to signin back to this account, even if he wanted.
                $user->account->delete();
            }
            else {
                // User is authenticated, and is not a guest, we can safely pass preloaded data
                $data['preloadedJson']['data'] = json_encode($this->accountService->fetchDataForUser());
            }
        }
        return view('front-end.index', $data);
    }
}