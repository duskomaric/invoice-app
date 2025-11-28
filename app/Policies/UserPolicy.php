<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission(PermissionEnum::USER_VIEW);
    }

    public function view(User $user, User $model): bool
    {
        return $user->hasPermission(PermissionEnum::USER_VIEW);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission(PermissionEnum::USER_CREATE);
    }

    public function update(User $user, User $model): bool
    {
        return $user->hasPermission(PermissionEnum::USER_EDIT);
    }

    public function delete(User $user, User $model): bool
    {
        return $user->hasPermission(PermissionEnum::USER_DELETE);
    }
}
