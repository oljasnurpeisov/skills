<?php

namespace Service\Auth;

use App\Models\Dialog;
use App\Models\PayInformation;
use App\Models\User;
use App\Models\UserInformation;
use Illuminate\Support\Facades\Auth;

class AuthService {
    /**
     * @var User
     */
    private $user;

    /**
     * AuthService constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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
            $user = $this->register($email);
        }

        Auth::login($user, true);
    }

    /**
     * Registration
     *
     * @param string $email
     * @return User
     */
    public function register(string $email): User
    {
        $user = $this->user->create(['email' => $email]);

        return $this->afterRegister($user);
    }

    /**
     * Create UserInformation/PayInformation/Dialog etc
     *
     * @param User $user
     * @return User
     */
    public function afterRegister(User $user): User
    {
        $this->createUserInformation($user);

        $this->createUserPayInformation($user);

        $user->roles()->sync([4]);

        $this->createSupportDialog($user);

        return $user;
    }

    /**
     * Create user information
     *
     * @param User $user
     * @return void
     */
    public function createUserInformation(User $user): void
    {
        $user_information               = new UserInformation;
        $user_information->user_id      = $user->id;
        $user_information->avatar       = $user->company_logo ?? null;
        $user_information->save();
    }

    /**
     * Create user pay information
     *
     * @param User $user
     * @return void
     */
    public function createUserPayInformation(User $user): void
    {
        $user_pay_information           = new PayInformation;
        $user_pay_information->user_id  = $user->id;
        $user_pay_information->save();
    }

    /**
     * Create support dialog
     *
     * @param User $user
     * @return void
     */
    public function createSupportDialog(User $user): void
    {
        $tech_support = User::whereHas('roles', function ($q) {
            $q->where('slug', '=', 'tech_support');
        })->first();

        $dialog = new Dialog;
        $dialog->save();

        $dialog->members()->sync([$user->id, $tech_support->id]);
    }
}
