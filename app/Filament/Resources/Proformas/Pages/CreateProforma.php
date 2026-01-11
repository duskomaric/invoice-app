<?php

namespace App\Filament\Resources\Proformas\Pages;

use App\Filament\Resources\Proformas\ProformaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProforma extends CreateRecord
{
    protected static string $resource = ProformaResource::class;

    public function mount(): void
    {
        parent::mount();

        $currency = request()->query('currency');
        if ($currency) {
            $this->data['currency'] = $currency;
        }
    }
}
