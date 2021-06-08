<?php

namespace Service\Auth;

use App\Models\User;
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
     *
     * @param User $user
     * @param RegisterService $registerService
     * @param LoginService $loginService
     */
    public function __construct(User $user, RegisterService $registerService, LoginService $loginService)
    {
        $this->user             = $user;
        $this->registerService  = $registerService;
        $this->loginService     = $loginService;
    }

    /**
     * Login by email
     *
     * @param string $email
     * @return void
     */
    public function loginByEmail(string $email): void
    {
        $user = $this->user->whereEmail($email)->first();

        if (empty($user)) {
            $user = $this->registerService->register(['email' => $email]);
        }

        $this->loginService->login($user);
    }
}
