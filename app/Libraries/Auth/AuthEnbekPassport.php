<?php

namespace Libraries\Auth;

use Illuminate\Support\Facades\Auth;
use Service\Auth\AuthService;

/**
 * Class AuthEnbekPassport
 * @author kgurovoy@gmail.com
 * @package Libraries\Auth
 */
class AuthEnbekPassport
{
    /**
     * @var EnbekPassport
     */
    private $enbekPassport;

    /**
     * @var AuthService
     */
    private $authService;

    /**
     * AuthEnbekPassport constructor.
     */
    public function __construct()
    {
        $this->authService      = new AuthService();

        $this->enbekPassport    = new EnbekPassport();
        $this->enbekPassport->init([
            'appName'   => config('auth.passportAppName'),
            'accessKey' => config('auth.passportAccessKey'),
        ]);
    }

    /**
     * Интеграция Enbek Passport
     *
     * @return void
     */
    public function init(): void
    {
        if ($this->isPassportAuth()) $this->loginUser();

        if (!$this->isPassportAuth()) $this->logout();
    }

    /**
     * Login user
     *
     * @return void
     */
    protected function loginUser(): void
    {
        if ($this->isStudent()) {
            if (!$this->checkUser()) $this->logout()->loginByEmail();
        } else {
            $this->loginByEmail();
        }
    }

    /**
     * Check user
     *
     * @return bool
     */
    protected function checkUser(): bool
    {
        return Auth::check() and $this->enbekPassport->user()->email === Auth::user()->email;
    }

    /**
     * Login student by email
     *
     * @return self
     */
    protected function loginByEmail(): self
    {
        $this->authService->loginStudentByEmail($this->enbekPassport->user()->email);

        return $this;
    }

    /**
     * Пользователь залогинен на passport.enbek.kz?
     *
     * @return bool
     */
    protected function isPassportAuth(): bool
    {
        return $this->enbekPassport->auth();
    }

    /**
     * Logout
     *
     * @return self
     */
    protected function logout(): self
    {
        if ($this->isStudent()) {
            Auth::logout();
        }

        return $this;
    }

    /**
     * Пользователь обучающийся?
     *
     * @return bool
     */
    protected function isStudent(): bool
    {
        return Auth::check() && Auth::user()->hasRole('student');
    }
}
