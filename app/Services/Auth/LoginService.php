<?php

namespace Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
}
