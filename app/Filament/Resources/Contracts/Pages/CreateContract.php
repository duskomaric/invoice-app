<?php

namespace App\Filament\Resources\Contracts\Pages;

use App\Filament\Resources\Contracts\ContractResource;
use Filament\Resources\Pages\CreateRecord;

class CreateContract extends CreateRecord
{
    protected static string $resource = ContractResource::class;

    public function mount(): void
    {
        parent::mount();

        $currency = request()->query('currency');
        if ($currency) {
            $this->data['currency'] = $currency;
        }
    }
}
