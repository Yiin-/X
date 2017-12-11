<?php

namespace App\Interfaces\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Validator;
use App\Interfaces\Http\Controllers\AbstractController;
use App\Domain\Service\Auth\AuthService;
use App\Interfaces\Http\Requests\Auth\RegisterRequest;
use App\Interfaces\Http\Requests\Auth\LoginRequest;
use App\Interfaces\Http\Requests\Auth\DemoRequest;
use App\Interfaces\Http\Requests\Auth\AcceptInvitationRequest;
use App\Domain\Model\Authentication\User\UserRepository;

class AuthController extends AbstractController
{
    private $authService;
    private $accountService;

    public function __construct(
        AuthService $authService
    ) {
        $this->authService = $authService;
    }

    public function validateField(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'sometimes|required',
            'first_name' => 'sometimes|required',
            'last_name' => 'sometimes|required',
            'email' => 'sometimes|required|email|unique:users,username',
            'site_address' => 'sometimes|required|unique:accounts',
            'password' => 'sometimes|required|confirmed'
        ]);

        return $validator->errors();
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

    public function acceptInvitation(AcceptInvitationRequest $request, UserRepository $userRepository)
    {
        $data = $request->get('data');

        $user = $userRepository->findByInvitationToken($data['invitation_token']);

        /**
         * Set new password for user
         */
        $password = $data['password'];

        if ($user->password === null) {
            $user->password = bcrypt($password);
            $user->save();
        }

        $this->authService->checkCredentials($user->account->site_address, $user->username, $password);

        /**
         * Employee details
         */
        $firstName = $data['first_name'];
        $lastName = $data['last_name'];
        $phone = $data['phone'];
        $jobTitle = $data['job_title'];
        $password = $data['password'];

        /**
         * Update employee
         */
        $user->authenticable->update([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'phone' => $phone,
            'job_title' => $jobTitle
        ]);

        /**
         * Mark that user accepted invitation
         * and unlock his account.
         */
        $user->acceptInvitation();

        return $this->authService->attemptLogin($user->account->site_address, $user->username, $password);
    }

    public function refresh()
    {
        return response()->json($this->authService->attemptRefresh());
    }

    public function unlock(Request $request)
    {
        $pin = $request->get('pin');;

        if (
            // auth()->user()->login_attempts < (int)config('auth.max_login_attempts') &&
            password_verify($pin, auth()->user()->pin_code)
        ) {
            auth()->user()->login_attempts = 0;
            auth()->user()->save();

            return 'OK';
        }
        else {
            auth()->user()->increment('login_attempts');

            if (auth()->user()->login_attempts < config('auth.max_login_attempts')) {
                return response()->json([
                    'message' => 'invalid_pin',
                    'attempts' => auth()->user()->login_attempts,
                    'max_attempts' => config('auth.max_login_attempts')
                ], 401);
            }
            else {
                auth()->user()->login_attempts = 0;
                auth()->user()->save();

                $this->authService->logout();

                return response([
                    'message' => 'invalid_token'
                ], 401);
            }
        }
    }

    public function heartbeat()
    {
        if (auth()->check()) {
            return 'OK';
        }
        return response([
            'message' => 'invalid_token'
        ], 401);
    }

    public function logout()
    {
        $this->authService->logout();

        return response()->json(null, 204);
    }
}