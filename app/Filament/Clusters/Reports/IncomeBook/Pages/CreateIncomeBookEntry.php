<?php

namespace App\Filament\Clusters\Reports\IncomeBook\Pages;

use App\Filament\Clusters\Reports\IncomeBook\IncomeBookEntryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateIncomeBookEntry extends CreateRecord
{
    protected static string $resource = IncomeBookEntryResource::class;
}
