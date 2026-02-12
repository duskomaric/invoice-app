<?php

namespace App\Filament\Clusters\Settings\Resources\EmailTemplateResource\Pages;

use App\Filament\Clusters\Settings\Resources\EmailTemplateResource\EmailTemplateResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmailTemplate extends CreateRecord
{
    protected static string $resource = EmailTemplateResource::class;
}
