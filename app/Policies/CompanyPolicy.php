<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\Response;

use App\Enums\RoleEnum;

class CompanyPolicy
{
    public function before(User $user, string $ability): bool|null
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
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Company $company): bool
    {
        return false;
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
