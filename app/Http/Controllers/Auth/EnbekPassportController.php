<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Libraries\Auth\AuthEnbekPassport;
use Service\Auth\AuthService;
use Services\Auth\LoginService;

class EnbekPassportController extends Controller
{
    /**
     * @var AuthService
     */
    private $authService;
    /**
     * @var AuthEnbekPassport
     */
    private $authEnbekPassport;
    /**
     * @var LoginService
     */
    private $loginService;

    /**
     * EnbekPassportController constructor.
     *
     * @param AuthService $authService
     * @param AuthEnbekPassport $authEnbekPassport
     * @param LoginService $loginService
     */
    public function __construct(AuthService $authService, AuthEnbekPassport $authEnbekPassport, LoginService $loginService)
    {
        $this->authService          = $authService;
        $this->authEnbekPassport    = $authEnbekPassport;
        $this->loginService         = $loginService;
    }

    /**
     * Login
     */
    public function login()
    {
        $this->authEnbekPassport->init();

        if (Auth::check()) {
            return redirect(url($this->loginService->redirect()));
        } else {
            return redirect(url('/'));
        }
    }
}
