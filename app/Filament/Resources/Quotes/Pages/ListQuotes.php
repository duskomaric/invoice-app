<?php

namespace App\Filament\Resources\Quotes\Pages;

use App\Filament\Resources\Quotes\QuoteResource;
use App\Models\CompanySetting;
use App\Models\Currency;
use App\Models\Quote;
use App\Services\DocumentNumberingService;
use Filament\Actions\CreateAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;

class ListQuotes extends ListRecords
{
    protected static string $resource = QuoteResource::class;

    protected function getHeaderActions(): array
    {
        $numbering = app(DocumentNumberingService::class);

        $tenantId = Filament::getTenant()?->id;
        $prefixSetting = (string) CompanySetting::get('invoice_numbering_prefix', 'currency', $tenantId);

        if ($prefixSetting !== 'currency') {
            return [
                CreateAction::make('create')
                    ->label(fn () => 'Nova ponuda (' . $numbering->assign(tap(new Quote(), function (Quote $quote) {
                        $quote->company_id = Filament::getTenant()?->id;
                        $quote->currency = null;
                        $quote->date = now();
                    }), ['prefix' => 'quote_prefix', 'year' => 'quote_year', 'number' => 'quote_number'], preview: true) . ')')
                    ->icon('heroicon-o-plus-circle')
                    ->color('primary')
                    ->url(fn (): string => static::getResource()::getUrl('create')),
            ];
        }

        $currencies = Currency::when($tenantId, fn ($q) => $q->where('company_id', $tenantId))
            ->orderBy('code')
            ->pluck('code')
            ->toArray();

        if ($currencies === []) {
            return [
                CreateAction::make('create')
                    ->label(fn () => 'Nova ponuda (' . $numbering->assign(tap(new Quote(), function (Quote $quote) {
                        $quote->company_id = Filament::getTenant()?->id;
                        $quote->currency = null;
                        $quote->date = now();
                    }), ['prefix' => 'quote_prefix', 'year' => 'quote_year', 'number' => 'quote_number'], preview: true) . ')')
                    ->icon('heroicon-o-plus-circle')
                    ->color('primary')
                    ->url(fn (): string => static::getResource()::getUrl('create')),
            ];
        }

        $actions = [];
        foreach ($currencies as $currency) {
            $actions[] = CreateAction::make('create_' . strtolower($currency))
                ->label(fn () => 'Nova ' . $currency . ' (' . $numbering->assign(tap(new Quote(), function (Quote $quote) use ($currency) {
                    $quote->company_id = Filament::getTenant()?->id;
                    $quote->currency = $currency;
                    $quote->date = now();
                }), ['prefix' => 'quote_prefix', 'year' => 'quote_year', 'number' => 'quote_number'], preview: true) . ')')
                ->icon('heroicon-o-plus-circle')
                ->color('primary')
                ->url(fn (): string => static::getResource()::getUrl('create', ['currency' => $currency]));
        }

        return $actions;
    }
}
