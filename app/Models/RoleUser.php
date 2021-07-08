<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class RoleUser
 * @package App\Models
 *
 * @property int $id
 * @property int $role_id
 * @property int $user_id
 *
 * @property Role $role
 */
class RoleUser extends Model
{
    protected $table = 'role_user';

    /**
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
