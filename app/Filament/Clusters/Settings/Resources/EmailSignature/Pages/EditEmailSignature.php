<?php

namespace App\Filament\Clusters\Settings\Resources\EmailSignature\Pages;

use App\Filament\Clusters\Settings\Resources\EmailSignature\EmailSignatureResource;
use Filament\Resources\Pages\EditRecord;

class EditEmailSignature extends EditRecord
{
    protected static string $resource = EmailSignatureResource::class;
}
