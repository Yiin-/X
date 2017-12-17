<?php

namespace App\Interfaces\Http\Controllers\Web;

use League\OAuth2\Server\Grant\PasswordGrant;
use Illuminate\Http\Request;
use App\Interfaces\Http\Controllers\AbstractController;
use App\Domain\Model\Authentication\Account\AccountRepository;
use App\Domain\Model\Authentication\User\UserRepository;
use App\Domain\Service\User\AccountService;
use App\Domain\Service\Auth\AuthService;
use App\Interfaces\Http\Controllers\Web\Traits\ManageSubdomains;

class WebController extends AbstractController
{
    use ManageSubdomains;

    private $accountService;
    private $accountRepository;
    private $authService;

    public function __construct(AccountService $accountService, AccountRepository $accountRepository, AuthService $authService)
    {
        $this->accountService = $accountService;
        $this->accountRepository = $accountRepository;
        $this->authService = $authService;
    }

    public function acceptInvitation(UserRepository $userRepository, $token)
    {
        /**
         * User is already logged in, log him out
         */
        if (auth()->check()) {
            // Revoke issued access token
            $this->authService->logout();
        }

        /**
         * Find user that invitation was delivered to.
         */
        $user = $userRepository->findByInvitationToken($token);

        /**
         * Invalid invitation token
         */
        if (!$user) {
            return $this->serveApplication();
        }

        if (/**
             * Redirect to company page
             */
            $this->getSubdomain() !== $user->account->site_address
        ) {
            return $this->redirectToUserAccount($user);
        }

        return $this->serveApplication([
            'user' => [
                'username' => $user->username,
                'is_password_set' => $user->password !== null,
                'personal_information' => $user->authenticable->transform()->toArray()
            ]
        ]);
    }

    /**
     * Confirm user by using token he got from email.
     *
     * At the moment there is no difference between confirmed
     * user and not.
     */
    public function confirmUser(UserRepository $userRepository, $token)
    {
        // Confirm user
        if ($user = $userRepository->findByConfirmationToken($token)) {
            $user->confirm();
        }
        return $this->serveApplication();
    }

    /**
     * Create new or login to existing demo account.
     */
    public function demo(Request $request)
    {
        /**
         * Try to login to existing guest account
         */
        if ($request->guest_key) {
            try {
                $data = $this->authService->attemptLogin($request->guest_key, 'demo', 'demo');
            } catch (\Exception $e) {}
        }
        /**
         * If user is already authenticated and not a guest,
         * serve application normally.
         */
        else if (auth()->check() && !auth()->user()->guest_key) {
            return $this->serveApplication();
        }

        /**
         * Or create a new one if login wasn't successful
         */
        if (!isset($data)) {
            $data = $this->authService->registerDemoAccount();
        }

        return view('front-end.index', $data);
    }

    /**
     * Used for routing.
     *
     * We're not referencing serveApplication method directly
     * in web routes file because first parameter is always
     * url path, e.g. /products or /logout, where in serveApplication
     * method we're expecting array.
     */
    public function application()
    {
        return $this->serveApplication();
    }

    /**
     * Pass preloaded data to front-end application
     * view, so there is no loading time for data.
     */
    private function serveApplication(array $customData = [])
    {
        $data = [
            'preloadedData' => $customData
        ];

        // Preload data only if user is authenticated
        if (auth()->check()) {
            $user = auth()->user();
            // Logout if that's a guest account and we're trying to access login or register page
            if ($user->guest_key && (request()->is('login') || request()->is('register'))) {
                // Revoke issued access token
                $this->authService->logout();
                // Also delete guest account, because there is no way for
                // user to login back to this demo account.
                $user->account->delete();
            }
            else {
                if (/**
                     * If it is demo account, subdomain should be "demo"
                     */
                    // ($user->guest_key && $this->getSubdomain() !== 'demo') ||
                    /**
                     * Else it should be site_address of user account
                     */
                    (!$user->guest_key && $this->getSubdomain() !== $user->account->site_address)
                ) {
                    return $this->redirectToUserAccount($user);
                }

                /**
                 * Preload data
                 *
                 * API Access information
                 */
                $data['preloadedData']['auth'] = [
                    'access_token' => request()->cookie('_accessToken')
                ];

                /**
                 * User and his account data
                 */
                $data['preloadedData']['account'] = $user->account->transform()->toArray();
                $data['preloadedData']['user'] = $user->transform()->parseIncludes(['settings', 'preferences', 'state'])->toArray();

                /**
                 * The rest of the data
                 */
                $data['preloadedData']['data'] = $this->accountService->fetchDataForUser();
            }
        }
        return view('front-end.index', $data);
    }
}