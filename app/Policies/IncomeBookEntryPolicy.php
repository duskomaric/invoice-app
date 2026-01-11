<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\IncomeBookEntry;
use App\Models\User;

class IncomeBookEntryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission(PermissionEnum::PAYMENT_VIEW);
    }

    public function view(User $user, IncomeBookEntry $model): bool
    {
        return $user->hasPermission(PermissionEnum::PAYMENT_VIEW);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission(PermissionEnum::PAYMENT_CREATE);
    }

    public function update(User $user, IncomeBookEntry $model): bool
    {
        return $user->hasPermission(PermissionEnum::PAYMENT_EDIT);
    }

    public function delete(User $user, IncomeBookEntry $model): bool
    {
        return $user->hasPermission(PermissionEnum::PAYMENT_DELETE);
    }
}
