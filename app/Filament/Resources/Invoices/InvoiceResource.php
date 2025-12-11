<?php

namespace App\Filament\Resources\Invoices;

use App\Filament\Resources\Invoices\Pages\CreateInvoice;
use App\Filament\Resources\Invoices\Pages\EditInvoice;
use App\Filament\Resources\Invoices\Pages\ListInvoices;
use App\Filament\Resources\Invoices\Schemas\InvoiceForm;
use App\Filament\Resources\Invoices\Tables\InvoiceTable;
use App\Models\Invoice;
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
        $prefixes = \App\Models\Setting::get('invoice_prefixes', ['BAM' => 'BAM', 'EUR' => 'EUR']);

        $tabs = [
            'all' => \Filament\Resources\Components\Tab::make('All Invoices'),
        ];

        foreach ($prefixes as $currency => $prefix) {
            $tabs[$currency] = \Filament\Resources\Components\Tab::make($currency)
                ->modifyQueryUsing(fn ($query) => $query->where('currency', $currency))
                ->badge(Invoice::where('currency', $currency)->count());
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
