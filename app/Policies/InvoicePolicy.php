<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\Invoice;
use App\Models\User;

class InvoicePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission(PermissionEnum::INVOICE_VIEW);
    }

    public function view(User $user, Invoice $model): bool
    {
        return $user->hasPermission(PermissionEnum::INVOICE_VIEW);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission(PermissionEnum::INVOICE_CREATE);
    }

    public function update(User $user, Invoice $model): bool
    {
        return $user->hasPermission(PermissionEnum::INVOICE_EDIT);
    }

    public function delete(User $user, Invoice $model): bool
    {
        return $user->hasPermission(PermissionEnum::INVOICE_DELETE);
    }
}
