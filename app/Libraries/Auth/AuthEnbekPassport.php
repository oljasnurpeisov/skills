<?php

namespace Libraries\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Service\Auth\AuthService;
use Services\Auth\LoginService;

/**
 * Class AuthEnbekPassport
 *
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
     * @var LoginService
     */
    private $loginService;

    /**
     * AuthEnbekPassport constructor.
     */
    public function __construct()
    {
        $this->authService      = new AuthService();
        $this->loginService     = new LoginService();

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
        if (!$this->checkResume() or !$this->checkAgree()) return;

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
     * @return void
     */
    protected function loginByEmail(): void
    {
        $this->authService->loginStudentByEmail($this->enbekPassport->user()->email);

        // Отправляем запрос в АПИ enbek.kz
        (new AuthStudent($this->enbekPassport->user()->token, $this->enbekPassport->user()->email, $this->enbekPassport->user()->uid))->afterLogin();

//        (new AuthStudent('b19b983d-ef69-43a4-a2f7-73a4a90f449b', 'kgurovoy@gmail.com', 123))->afterLogin();
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

    /**
     * Есть резюме?
     *
     * @return bool
     */
    protected function checkResume(): bool
    {
        return empty(Session::get('resume_data'));
    }

    /**
     * Есть резюме?
     *
     * @return bool
     */
    protected function checkAgree(): bool
    {
        return empty(Session::get('agree_data'));
    }
}
