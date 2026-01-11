<?php

namespace App\Observers;

use App\Models\EmailTemplate;

class EmailTemplateObserver
{
    public function saving(EmailTemplate $emailTemplate): void
    {
        if (! $emailTemplate->is_default) {
            return;
        }

        EmailTemplate::where('company_id', $emailTemplate->company_id)
            ->where('type', $emailTemplate->type)
            ->when($emailTemplate->id, fn ($query) => $query->where('id', '!=', $emailTemplate->id))
            ->update(['is_default' => false]);
    }
}
