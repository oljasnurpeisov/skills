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

    /**
     * Перенаправление после входа (перенесено из LoginController)
     *
     * @return string
     */
    public function redirect(): string
    {
        if (Auth::user()->hasRole('admin', 'tech_support', 'moderator', 'yurist', 'planirovshchik', 'buhgalter', 'rukovoditel')) {
            return '/'. app()->getLocale() .'/admin';
        } else if (Auth::user()->hasRole('author')) {
            return '/' . app()->getLocale() . '/profile-author-information';
        } else {
            return '/';
        }
    }
}
