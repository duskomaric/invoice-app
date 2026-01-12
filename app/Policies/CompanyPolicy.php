<?php

namespace App\Policies;

use App\Enums\RoleEnum;
use App\Models\Company;
use App\Models\User;

class CompanyPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->role === RoleEnum::SuperAdmin) {
            return true;
        }

        return null; // Default denial via methods below
    }

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Company $company): bool
    {
        return $user->role === RoleEnum::Administrator && $user->companies->contains($company);
    }

    public function create(User $user): bool
    {
        return $user->role === RoleEnum::Administrator;
    }

    public function update(User $user, Company $company): bool
    {
        return $user->role === RoleEnum::Administrator && $user->companies->contains($company);
    }

    public function delete(User $user, Company $company): bool
    {
        return false;
    }

    public function restore(User $user, Company $company): bool
    {
        return false;
    }

    public function forceDelete(User $user, Company $company): bool
    {
        return false;
    }
}
