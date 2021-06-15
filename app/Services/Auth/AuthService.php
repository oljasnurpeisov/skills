<?php

namespace Service\Auth;

use App\Models\User;
use Carbon\Carbon;
use Services\Auth\LoginService;
use Services\Auth\RegisterService;

/**
 * Class AuthService
 * @author kgurovoy@gmail.com
 * @package Service\Auth
 */

class AuthService {
    /**
     * @var User
     */
    private $user;

    /**
     * @var RegisterService
     */
    private $registerService;

    /**
     * @var LoginService
     */
    private $loginService;

    /**
     * AuthService constructor.
     */
    public function __construct()
    {
        $this->user             = new User();
        $this->registerService  = new RegisterService();
        $this->loginService     = new LoginService();
    }

    /**
     * Login by email
     *
     * @param string $email
     * @return void
     */
    public function loginStudentByEmail(string $email): void
    {
        $user = $this->user->whereEmail($email)->first();

        if (empty($user)) {
            $user = $this->registerService->register(
                [
                    'email'             => $email,
                    'is_activate'       => 1,
                    'email_verified_at' => Carbon::now()->toDateTimeString()
                ], 5);
        }

        $this->loginService->login($user);
    }
}
