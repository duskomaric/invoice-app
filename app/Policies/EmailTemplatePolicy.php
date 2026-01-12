<?php

namespace App\Policies;

use App\Models\EmailTemplate;
use App\Models\User;

class EmailTemplatePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->companies()->exists();
    }

    public function view(User $user, EmailTemplate $emailTemplate): bool
    {
        return $user->companies->contains($emailTemplate->company_id);
    }

    public function create(User $user): bool
    {
        return $user->companies()->exists();
    }

    public function update(User $user, EmailTemplate $emailTemplate): bool
    {
        return $user->companies->contains($emailTemplate->company_id);
    }

    public function delete(User $user, EmailTemplate $emailTemplate): bool
    {
        return $user->companies->contains($emailTemplate->company_id);
    }

    public function restore(User $user, EmailTemplate $emailTemplate): bool
    {
        return $user->companies->contains($emailTemplate->company_id);
    }

    public function forceDelete(User $user, EmailTemplate $emailTemplate): bool
    {
        return $user->companies->contains($emailTemplate->company_id);
    }
}
