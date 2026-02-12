<?php

namespace App\Filament\Clusters\Reports\IncomeBook\Pages;

use App\Filament\Clusters\Reports\IncomeBook\IncomeBookEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIncomeBookEntry extends EditRecord
{
    protected static string $resource = IncomeBookEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
