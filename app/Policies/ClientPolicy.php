<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\Client;
use App\Models\User;

class ClientPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission(PermissionEnum::CLIENT_VIEW);
    }

    public function view(User $user, Client $model): bool
    {
        return $user->hasPermission(PermissionEnum::CLIENT_VIEW);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission(PermissionEnum::CLIENT_CREATE);
    }

    public function update(User $user, Client $model): bool
    {
        return $user->hasPermission(PermissionEnum::CLIENT_EDIT);
    }

    public function delete(User $user, Client $model): bool
    {
        return $user->hasPermission(PermissionEnum::CLIENT_DELETE);
    }
}
