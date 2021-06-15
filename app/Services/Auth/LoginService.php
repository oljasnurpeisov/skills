<?php

namespace Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * Class LoginService
 * @author kgurovoy@gmail.com
 * @package Services\Auth
 */
class LoginService {
    /**
     * Login user
     *
     * @param User $user
     */
    public function login(User $user): void
    {
        Auth::login($user, true);
    }

    /**
     * Logout
     */
    public function logout(): void
    {
        Auth::logout();
    }
}
