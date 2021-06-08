<?php

namespace Service\Author;

use App\Models\User;
use Illuminate\Support\Arr;

/**
 * Class UpdateRequisitesService
 * @author kgurovoy@gmail.com
 * @package Service\Author
 */
class UpdateRequisitesService
{
    /**
     * @var User
     */
    private $user;

    /**
     * Update RequisitesService constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Update user requisites data
     *
     * @param int $user_id
     * @param array $data
     */
    public function update(int $user_id, array $data): void
    {
        $this->user->find($user_id)->update($data);
    }
}
