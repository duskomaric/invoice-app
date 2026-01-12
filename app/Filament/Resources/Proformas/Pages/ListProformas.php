<?php

namespace App\Filament\Resources\Proformas\Pages;

use App\Filament\Resources\Proformas\ProformaResource;
use App\Models\CompanySetting;
use App\Models\Currency;
use App\Models\Proforma;
use App\Services\DocumentNumberingService;
use Filament\Actions\CreateAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;

class ListProformas extends ListRecords
{
    protected static string $resource = ProformaResource::class;

    protected function getHeaderActions(): array
    {
        $numbering = app(DocumentNumberingService::class);

        $tenantId = Filament::getTenant()?->id;
        $prefixSetting = (string) CompanySetting::get('invoice_numbering_prefix', 'currency', $tenantId);

        if ($prefixSetting !== 'currency') {
            return [
                CreateAction::make('create')
                    ->label(fn () => 'Novi predračun ('.$numbering->assign(tap(new Proforma, function (Proforma $proforma) {
                        $proforma->company_id = Filament::getTenant()?->id;
                        $proforma->currency = null;
                        $proforma->date = now();
                    }), ['prefix' => 'proforma_prefix', 'year' => 'proforma_year', 'number' => 'proforma_number'], preview: true).')')
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
                    ->label(fn () => 'Novi predračun ('.$numbering->assign(tap(new Proforma, function (Proforma $proforma) {
                        $proforma->company_id = Filament::getTenant()?->id;
                        $proforma->currency = null;
                        $proforma->date = now();
                    }), ['prefix' => 'proforma_prefix', 'year' => 'proforma_year', 'number' => 'proforma_number'], preview: true).')')
                    ->icon('heroicon-o-plus-circle')
                    ->color('primary')
                    ->url(fn (): string => static::getResource()::getUrl('create')),
            ];
        }

        $actions = [];
        foreach ($currencies as $currency) {
            $actions[] = CreateAction::make('create_'.strtolower($currency))
                ->label(fn () => 'Novi '.$currency.' ('.$numbering->assign(tap(new Proforma, function (Proforma $proforma) use ($currency) {
                    $proforma->company_id = Filament::getTenant()?->id;
                    $proforma->currency = $currency;
                    $proforma->date = now();
                }), ['prefix' => 'proforma_prefix', 'year' => 'proforma_year', 'number' => 'proforma_number'], preview: true).')')
                ->icon('heroicon-o-plus-circle')
                ->color('primary')
                ->url(fn (): string => static::getResource()::getUrl('create', ['currency' => $currency]));
        }

        return $actions;
    }
}
