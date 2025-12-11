<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission(PermissionEnum::PAYMENT_VIEW);
    }

    public function view(User $user, Payment $model): bool
    {
        return $user->hasPermission(PermissionEnum::PAYMENT_VIEW);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission(PermissionEnum::PAYMENT_CREATE);
    }

    public function update(User $user, Payment $model): bool
    {
        return $user->hasPermission(PermissionEnum::PAYMENT_EDIT);
    }

    public function delete(User $user, Payment $model): bool
    {
        return $user->hasPermission(PermissionEnum::PAYMENT_DELETE);
    }
}
