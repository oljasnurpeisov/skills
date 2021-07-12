<?php

namespace App\Services\Users;

use App\Models\UserOkedActivity;
use App\Models\UserOkedIndustry;
use Illuminate\Support\Facades\Auth;

class UserService
{
    /**
     * @var UserOkedIndustry
     */
    private $userOkedIndustry;
    /**
     * @var UserOkedActivity
     */
    private $userOkedActivity;

    /**
     * UserService constructor.
     *
     * @param UserOkedIndustry $userOkedIndustry
     * @param UserOkedActivity $userOkedActivity
     */
    public function __construct(UserOkedIndustry $userOkedIndustry, UserOkedActivity $userOkedActivity)
    {
        $this->userOkedIndustry = $userOkedIndustry;
        $this->userOkedActivity = $userOkedActivity;
    }

    /**
     * Сохраняем ОКЕД
     *
     * @param array $oked_industries
     * @param array $oked_activities
     */
    public function saveOked(array $oked_industries, array $oked_activities): void
    {
        $this->userOkedIndustry->whereUserId(Auth::user()->id)->delete();
        foreach ($oked_industries as $industry) {
            $this->userOkedIndustry->create(['oked_industries_id' => $industry, 'user_id' => Auth::user()->id]);
        }

        $this->userOkedActivity->whereUserId(Auth::user()->id)->delete();
        foreach ($oked_activities as $activity) {
            $this->userOkedActivity->create(['oked_activities_id' => $activity, 'user_id' => Auth::user()->id]);
        }
    }
}
