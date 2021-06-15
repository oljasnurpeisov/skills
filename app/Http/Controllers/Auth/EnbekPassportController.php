<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Libraries\Auth\AuthEnbekPassport;
use Service\Auth\AuthService;

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
     * EnbekPassportController constructor.
     *
     * @param AuthService $authService
     * @param AuthEnbekPassport $authEnbekPassport
     */
    public function __construct(AuthService $authService, AuthEnbekPassport $authEnbekPassport)
    {
        $this->authService          = $authService;
        $this->authEnbekPassport    = $authEnbekPassport;
    }

    /**
     * Login
     */
    public function login()
    {
        $this->authEnbekPassport->init();

        if (Auth::check()) {
            return redirect(url((new LoginController())->redirectTo()));
        } else {
            return redirect(url('/'));
        }
    }
}
