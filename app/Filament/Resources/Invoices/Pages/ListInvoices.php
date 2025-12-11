<?php

namespace App\Filament\Resources\Invoices\Pages;

use App\Filament\Resources\Invoices\InvoiceResource;
use App\Models\Invoice;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListInvoices extends ListRecords
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        $prefixes = \App\Models\Setting::get('invoice_prefixes');
        $actions = [];

        foreach ($prefixes as $currency => $prefix) {
            $actions[] = CreateAction::make("create_{$currency}")
                ->label("New {$currency} Invoice")
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
        $tabs = [
            'all' => Tab::make('All')
                ->badge($this->getModel()::count())
                ->modifyQueryUsing(fn (Builder $query) => $query) // No filter for "All"
        ];

        $currencies = $this->getModel()::distinct()->pluck('currency');
        foreach ($currencies as $currency) {
            $count = $this->getModel()::where('currency', $currency)->count();

            $tabs[$currency] = Tab::make($currency)
                ->badge($count)
                ->modifyQueryUsing(fn (Builder $query) => $query->where('currency', $currency));
        }

        return $tabs;
    }
}
