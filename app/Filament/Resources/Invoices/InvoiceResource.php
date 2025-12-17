<?php

namespace App\Filament\Resources\Invoices;

use App\Filament\Resources\Invoices\Pages\CreateInvoice;
use App\Filament\Resources\Invoices\Pages\EditInvoice;
use App\Filament\Resources\Invoices\Pages\ListInvoices;
use App\Filament\Resources\Invoices\Schemas\InvoiceForm;
use App\Filament\Resources\Invoices\Tables\InvoiceTable;
use App\Models\Currency;
use App\Models\Invoice;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-document-text';

    public static function form(Schema $schema): Schema
    {
        return InvoiceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InvoiceTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return \App\Filament\Resources\Invoices\Schemas\InvoiceInfolist::configure($schema);
    }

    public static function getTabs(): array
    {
        $tenantId = Filament::getTenant()?->id;

        $currencies = Currency::when($tenantId, fn ($q) => $q->where('company_id', $tenantId))
            ->orderBy('code')
            ->pluck('code')
            ->toArray();

        $tabs = [
            'all' => \Filament\Resources\Components\Tab::make('All Invoices'),
        ];

        foreach ($currencies as $currency) {
            $tabs[$currency] = \Filament\Resources\Components\Tab::make($currency)
                ->modifyQueryUsing(fn ($query) => $query->where('currency', $currency))
                ->badge(Invoice::when($tenantId, fn ($q) => $q->where('company_id', $tenantId))
                    ->where('currency', $currency)
                    ->count());
        }

        return $tabs;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInvoices::route('/'),
            'create' => CreateInvoice::route('/create'),
            'view' => \App\Filament\Resources\Invoices\Pages\ViewInvoice::route('/{record}'),
            'edit' => EditInvoice::route('/{record}/edit'),
        ];
    }
}
