<?php

namespace App\Domain\Service\Auth;

use Optimus\ApiConsumer\Router as ApiConsumer;
use App\Domain\Service\User\AccountService;
use App\Domain\Model\Authentication\User\UserRepository;
use App\Application\Exceptions\Auth\InvalidCredentialsException;
use Auth;
use Cookie;
use DB;

class AuthService
{
    const REFRESH_TOKEN = 'refreshToken';

    protected $apiConsumer;
    protected $accountService;
    protected $userRepository;

    public function __construct(ApiConsumer $apiConsumer, AccountService $accountService, UserRepository $userRepository)
    {
        $this->apiConsumer = $apiConsumer;
        $this->accountService = $accountService;
        $this->userRepository = $userRepository;
    }

    public function registerDemoAccount()
    {
        $guestKey = $this->accountService->createNewDemoAccount();

        return $this->attemptLogin($guestKey, 'demo', 'demo', true);
    }

    public function register(
        $companyName,
        $companyEmail,
        $siteAddress,
        $firstName,
        $lastName,
        $userEmail,
        $userPassword
    ) {
        $this->accountService->createNewAccount(
            $companyName, $companyEmail, $siteAddress, $firstName, $lastName, $userEmail, $userPassword
        );

        return $this->attemptLogin($siteAddress, $userEmail, $userPassword);
    }

    /**
     * Attempt to create an access token using user credentials
     *
     * @param string $siteAddress
     * @param string $username
     * @param string $password
     * @return array
     */
    public function attemptLogin($siteAddress, $username, $password, $isGuest = false)
    {
        $user = $this->userRepository->findByUsername($siteAddress, $username);

        if (!is_null($user)) {
            $data = $this->proxy('password', [
                'username' => $user->uuid,
                'password' => $password
            ]);

            $data['user'] = $user;
            $data['user']['company'] = $user->account->uuid;
            $data['user']['profile'] = $user->profile;
            $data['user']['settings'] = $user->settings;
            $data['user']['preferences'] = $user->preferences;

            if ($isGuest) {
                $data['user']['guest_key'] = $siteAddress;
            }
            $data['preloadedData'] = [
                'data' => $this->accountService->fetchDataForUser($user)
            ];

            return $data;
        }

        throw new InvalidCredentialsException('invalid_credentials');
    }

    /**
     * Attempt to refresh the access token used a refresh token that
     * has been saved in a cookie
     */
    public function attemptRefresh()
    {
        $refreshToken = request()->cookie(self::REFRESH_TOKEN);

        return $this->proxy('refresh_token', [
            'refresh_token' => $refreshToken
        ]);
    }

    /**
     * Proxy a request to the OAuth server.
     *
     * @param string $grantType what type of grant type should be proxied
     * @param array $data the data to send to the server
     * @return array
     */
    public function proxy($grantType, array $data = [])
    {
        $data = array_merge($data, [
            'client_id'     => env('PASSWORD_CLIENT_ID'),
            'client_secret' => env('PASSWORD_CLIENT_SECRET'),
            'grant_type'    => $grantType
        ]);

        $response = $this->apiConsumer->post('/oauth/token', $data);

        if (!$response->isSuccessful()) {
            if ($grantType === 'password') {
                throw new InvalidCredentialsException('invalid_credentials');
            }
            throw new InvalidCredentialsException('invalid_token');
        }

        $data = json_decode($response->getContent());

        // Create a refresh token cookie
        Cookie::queue(
            self::REFRESH_TOKEN,
            $data->refresh_token,
            864000, // 10 days
            null,
            null,
            false,
            true // HttpOnly
        );
        Cookie::queue(
            '_accessToken',
            $data->access_token,
            864000,
            null,
            null,
            false,
            true
        );

        return [
            'access_token' => $data->access_token,
            'expires_in' => $data->expires_in
        ];
    }

    /**
     * Logs out the user. We revoke access token and refresh token.
     * Also instruct the client to forget the refresh cookie.
     */
    public function logout()
    {
        if (auth()->check()) {
            $accessToken = auth()->user()->token();

            DB::table('oauth_refresh_tokens')
                ->where('access_token_id', $accessToken->id)
                ->update([
                    'revoked' => true
                ]);

            $accessToken->revoke();
        }

        Cookie::queue(Cookie::forget(self::REFRESH_TOKEN));
    }
}