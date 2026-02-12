<?php

namespace App\Observers;

use App\Models\EmailSignature;

class EmailSignatureObserver
{
    public function saving(EmailSignature $emailSignature): void
    {
        if (! $emailSignature->is_default) {
            return;
        }

        EmailSignature::where('company_id', $emailSignature->company_id)
            ->when($emailSignature->id, fn ($query) => $query->where('id', '!=', $emailSignature->id))
            ->update(['is_default' => false]);
    }
}
