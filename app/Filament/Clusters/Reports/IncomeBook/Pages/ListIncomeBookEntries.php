<?php

namespace App\Filament\Clusters\Reports\IncomeBook\Pages;

use App\Filament\Clusters\Reports\IncomeBook\IncomeBookEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIncomeBookEntries extends ListRecords
{
    protected static string $resource = IncomeBookEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
