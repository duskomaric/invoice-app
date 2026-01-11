<?php

namespace App\Filament\Resources\Quotes\Pages;

use App\Filament\Resources\Quotes\QuoteResource;
use Filament\Resources\Pages\CreateRecord;

class CreateQuote extends CreateRecord
{
    protected static string $resource = QuoteResource::class;

    public function mount(): void
    {
        parent::mount();

        $currency = request()->query('currency');
        if ($currency) {
            $this->data['currency'] = $currency;
        }
    }
}
