<?php

namespace App\Filament\Clusters\Settings\Resources\EmailSignature\Pages;

use App\Filament\Clusters\Settings\Resources\EmailSignature\EmailSignatureResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmailSignature extends CreateRecord
{
    protected static string $resource = EmailSignatureResource::class;
}
