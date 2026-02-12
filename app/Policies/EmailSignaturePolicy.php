<?php

namespace App\Policies;

use App\Models\EmailSignature;
use App\Models\User;
use Illuminate\Auth\Access\Response;

use App\Enums\RoleEnum;

class EmailSignaturePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->companies()->exists();
    }

    public function view(User $user, EmailSignature $emailSignature): bool
    {
        return $user->companies->contains($emailSignature->company_id);
    }

    public function create(User $user): bool
    {
        return $user->companies()->exists();
    }

    public function update(User $user, EmailSignature $emailSignature): bool
    {
        return $user->companies->contains($emailSignature->company_id);
    }

    public function delete(User $user, EmailSignature $emailSignature): bool
    {
        return $user->companies->contains($emailSignature->company_id);
    }

    public function restore(User $user, EmailSignature $emailSignature): bool
    {
        return $user->companies->contains($emailSignature->company_id);
    }

    public function forceDelete(User $user, EmailSignature $emailSignature): bool
    {
        return $user->companies->contains($emailSignature->company_id);
    }
}
