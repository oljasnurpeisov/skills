<?php

namespace Services\Auth;

use Illuminate\Support\Facades\Auth;

class LoginService {
    /**
     * Login user
     *
     * @param object $user
     */
    public function login(object $user)
    {
        Auth::login($user, true);
    }
}
