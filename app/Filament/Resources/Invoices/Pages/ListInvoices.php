<?php

namespace App\Filament\Resources\Invoices\Pages;

use App\Filament\Resources\Invoices\InvoiceResource;
use App\Models\CompanySetting;
use App\Models\Currency;
use App\Models\Invoice;
use App\Services\InvoiceNumberingService;
use Filament\Facades\Filament;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListInvoices extends ListRecords
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        $numbering = app(InvoiceNumberingService::class);

        $tenantId = Filament::getTenant()?->id;
        $prefixSetting = (string) CompanySetting::get('invoice_numbering_prefix', 'currency', $tenantId);

        if ($prefixSetting !== 'currency') {
            return [
                CreateAction::make('create')
                    ->label(fn () => 'New Invoice (' . $numbering->preview(null, now()) . ')')
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
                    ->label(fn () => 'New Invoice (' . $numbering->preview(null, now()) . ')')
                    ->icon('heroicon-o-plus-circle')
                    ->color('primary')
                    ->url(fn (): string => static::getResource()::getUrl('create')),
            ];
        }

        $actions = [];
        foreach ($currencies as $currency) {
            $actions[] = CreateAction::make('create_' . strtolower($currency))
                ->label(fn () => 'New ' . $currency . ' (' . $numbering->preview($currency, now()) . ')')
                ->icon('heroicon-o-plus-circle')
                ->color('primary')
                ->url(fn (): string => static::getResource()::getUrl('create', ['currency' => $currency]));
        }

        return $actions;
    }

    public function getTabs(): array
    {
        $tenantId = Filament::getTenant()?->id;

        $invoiceQuery = Invoice::query()->when($tenantId, fn (Builder $q) => $q->where('company_id', $tenantId));

        $tabs = [
            'all' => Tab::make('All')
                ->badge($invoiceQuery->clone()->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->when($tenantId, fn (Builder $q) => $q->where('company_id', $tenantId))),
        ];

        $currencies = Currency::when($tenantId, fn ($q) => $q->where('company_id', $tenantId))
            ->orderBy('code')
            ->pluck('code')
            ->toArray();

        foreach ($currencies as $currency) {
            $tabs[$currency] = Tab::make($currency)
                ->badge($invoiceQuery->clone()->where('currency', $currency)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->when($tenantId, fn (Builder $q) => $q->where('company_id', $tenantId))
                    ->where('currency', $currency));
        }

        return $tabs;
    }
}
