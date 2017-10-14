<?php

namespace App\Interfaces\Http\Controllers\Web;

use League\OAuth2\Server\Grant\PasswordGrant;
use Illuminate\Http\Request;
use App\Interfaces\Http\Controllers\AbstractController;
use App\Domain\Model\Authentication\Account\AccountRepository;
use App\Domain\Model\Authentication\User\UserRepository;
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
        if ($user = $userRepository->newQuery()->where('confirmation_token', $token)->first()) {
            $user->update([ 'confirmation_token' => null ]);
        }
        return $this->serveApplication();
    }

    public function demo(Request $request)
    {
        $data = [
            'preloadedJson' => []
        ];

        /**
         * Try to login to existing guest account
         */
        if ($request->guest_key) {
            try {
                $authData = $this->authService->attemptLogin($request->guest_key, 'demo', 'demo');
                $data['preloadedJson']['user'] = $authData['user'];
            } catch (\Exception $e) {}
        } else if (auth()->check() && !auth()->user()->guest_key) {
            return $this->serveApplication();
        }
        /**
         * Or create a new one if login wasn't successful
         */
        if (!array_key_exists('user', $data['preloadedJson'])) {
            $authData = $this->authService->registerDemoAccount();
            $user = $authData['user'];
            $data['preloadedJson']['user'] = $user;
            $data['preloadedJson']['user']['site_address'] = $user->account->site_address;
            $data['preloadedJson']['user']['company'] = $user->account->uuid;
            $data['preloadedJson']['user']['profile'] = $user->profile;
            $data['preloadedJson']['user']['settings'] = $user->settings;
            $data['preloadedJson']['user']['preferences'] = $user->preferences;
        }

        $data['preloadedJson']['access_token'] = $authData['access_token'];
        $data['preloadedJson']['data'] = $authData['preloadedData']['data'];

        return view('front-end.index', $data);
        // $client = $this->validateClient($request);
        // $scopes = $this->validateScopes($this->getRequestParameter('scope', $request));
        // $user = $this->validateUser($request, $client);
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
            if ($user->guest_key && (request()->is('login') || request()->is('register') || request()->is('/'))) {
                // Revoke issued access token
                $this->authService->logout();
                // Also delete guest account, because there is no way for
                // user to signin back to this account, even if he wanted.
                $user->account->delete();
            }
            else {
                // TODO: Rewrite this mess or move somewhere else
                $scheme = request()->getScheme();
                $host = request()->getHttpHost();
                preg_match('/(?:http[s]*\:\/\/)*(.*?)\.(?=[^\/]*\..{2,5})/i', $host, $matches)[1];
                if (!array_key_exists(1, $matches) || $matches[1] !== $user->account->site_address) {
                    preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', config('app.url'), $regs);
                    return redirect($scheme . '://' . $user->account->site_address . '.' . $regs['domain'] . request()->getRequestUri());
                }
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