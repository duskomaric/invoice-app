<?php

namespace App\Filament\Resources\Invoices\Pages;

use App\Filament\Clusters\Settings\Resources\Currencies\CurrencyResource;
use App\Filament\Resources\Invoices\InvoiceResource;
use App\Models\Currency;
use App\Models\Invoice;
use App\Services\InvoiceNumberingService;
use Filament\Facades\Filament;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListInvoices extends ListRecords
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        $tenantId = Filament::getTenant()?->id;
        $currencies = Currency::when($tenantId, fn ($q) => $q->where('company_id', $tenantId))
            ->orderBy('code')
            ->pluck('code')
            ->toArray();

        $actions = [];

        $numbering = app(InvoiceNumberingService::class);

        if (! $numbering->usesPerCurrencySequence()) {
            return [
                CreateAction::make('create')
                    ->label(fn () => 'New Invoice (' . $numbering->preview(null, now()) . ')')
                    ->icon('heroicon-o-plus-circle')
                    ->color('primary')
                    ->url(fn (): string => static::getResource()::getUrl('create')),
            ];
        }

        if (empty($currencies)) {
            return [
                Action::make('add_currency')
                    ->label('Add Currency')
                    ->icon('heroicon-o-plus-circle')
                    ->color('primary')
                    ->url(fn (): string => CurrencyResource::getUrl('create')),
            ];
        }

        foreach ($currencies as $currency) {
            $actions[] = CreateAction::make("create_{$currency}")
                ->label(fn () => 'New ' . $currency . ' Invoice (' . $numbering->preview($currency, now()) . ')')
                ->icon('heroicon-o-plus-circle')
                ->color(match($currency) {
                    'EUR' => 'warning',
                    'BAM' => 'success',
                    default => 'primary',
                })
                ->mutateDataUsing(function (array $data) use ($currency) {
                    $data['currency'] = $currency;
                    return $data;
                })
                ->url(fn (): string => static::getResource()::getUrl('create', ['currency' => $currency]));
        }

        return $actions;
    }

    public function getTabs(): array
    {
        $tenantId = Filament::getTenant()?->id;
//        $numbering = app(InvoiceNumberingService::class);

        $invoiceQuery = Invoice::query()->when($tenantId, fn (Builder $q) => $q->where('company_id', $tenantId));

        $tabs = [
            'all' => Tab::make('All')
                ->badge($invoiceQuery->clone()->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->when($tenantId, fn (Builder $q) => $q->where('company_id', $tenantId))),
        ];

//        if (! $numbering->usesPerCurrencySequence()) {
//            return $tabs;
//        }

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
