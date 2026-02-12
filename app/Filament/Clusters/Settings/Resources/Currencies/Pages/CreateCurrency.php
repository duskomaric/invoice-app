<?php

namespace App\Filament\Clusters\Settings\Resources\Currencies\Pages;

use App\Filament\Clusters\Settings\Resources\Currencies\CurrencyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCurrency extends CreateRecord
{
    protected static string $resource = CurrencyResource::class;
}
