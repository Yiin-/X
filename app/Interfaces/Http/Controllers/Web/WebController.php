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

    public function confirmUser(UserRepository $userRepository, $token)
    {
        // Confirm user
        if ($user = $userRepository->where('confirmation_token', $token)->first()) {
            $user->update([ 'confirmation_token' => null ]);
        }
        return $this->serveApplication();
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
            if (false && $user->guest_key) {
                // Revoke issued access token
                $this->authService->logout();
                // Also delete guest account, because there is no way for
                // user to signin back to this account, even if he wanted.
                $user->account->delete();
            }
            else {
                $data['preloadedJson']['access_token'] = request()->cookie('_accessToken');
                $data['preloadedJson']['user'] = $user;
                $data['preloadedJson']['user']['site_address'] = $user->account->site_address;
                $data['preloadedJson']['user']['company'] = $user->account->uuid;
                $data['preloadedJson']['user']['profile'] = $user->profile;
                $data['preloadedJson']['user']['settings'] = $user->settings;
                $data['preloadedJson']['user']['preferences'] = $user->preferences;

                // User is authenticated, and is not a guest, we can safely pass preloaded data
                $data['preloadedJson']['data'] = $this->accountService->fetchDataForUser();
            }
        }
        return view('front-end.index', $data);
    }
}