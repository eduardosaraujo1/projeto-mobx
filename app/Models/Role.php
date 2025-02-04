<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Role extends Pivot
{
    /**
     * Determine if the user has specific permission level(s)
     * @param array<UserRole>|string $roles the role(s) to check against the user
     * @return bool
     */
    public function hasPermission(array|string $roles): bool
    {
        return in_array($this->role, (array) $roles);
    }
}
