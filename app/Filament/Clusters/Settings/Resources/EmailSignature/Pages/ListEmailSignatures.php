<?php

namespace App\Filament\Clusters\Settings\Resources\EmailSignature\Pages;

use App\Filament\Clusters\Settings\Resources\EmailSignature\EmailSignatureResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmailSignatures extends ListRecords
{
    protected static string $resource = EmailSignatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
