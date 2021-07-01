<?php

namespace App\Policies\Contracts;

use App\Models\Contract;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class AdminContractPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Доступ только для модератора
     *
     * @return bool
     */
    public function moderatorOnly(): bool
    {
        return Auth::user()->hasRole('moderator');
    }

    /**
     * Расторжение договора директором
     *
     * @param User $user
     * @param Contract $contract
     * @return bool
     */
    public function rejectContract(User $user, Contract $contract): bool
    {
        return $contract->isSigned() and $contract->isQuota() and Auth::user()->hasRole('rukovoditel');
    }

    /**
     * Отмена отклонения договора администрацией
     *
     * @param User $user
     * @param Contract $contract
     * @return bool
     */
    public function rejectContractByAdminCancel(User $user, Contract $contract): bool
    {
        return $contract->isRejectedByAdmin() and Auth::user()->hasRole('moderator');
    }

    /**
     * Отклонение договора модератором
     *
     * @param User $user
     * @param Contract $contract
     * @return bool
     */
    public function rejectContractByModerator(User $user, Contract $contract): bool
    {
        return ($contract->isRejectedByAdmin() or $contract->isPending()) and Auth::user()->hasRole('moderator');
    }
}
