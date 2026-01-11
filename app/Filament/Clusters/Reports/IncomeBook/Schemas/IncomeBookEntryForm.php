<?php

namespace App\Filament\Clusters\Reports\IncomeBook\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class IncomeBookEntryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Osnovni podaci')
                    ->schema([
                        DatePicker::make('booking_date')
                            ->label('Datum knjiženja')
                            ->required()
                            ->default(now()),
                        Textarea::make('description')
                            ->label('Opis promjene (dokumenti)')
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Prihodi po kategorijama')
                    ->schema([
                        \App\Filament\Components\MoneyInput::make('income_products')
                            ->label('Naplaćeni prihodi od proizvoda')
                            ->prefix('KM')
                            ->default(0),
                        \App\Filament\Components\MoneyInput::make('income_goods')
                            ->label('Naplaćeni prihodi od robe')
                            ->prefix('KM')
                            ->default(0),
                        \App\Filament\Components\MoneyInput::make('income_services')
                            ->label('Naplaćeni prihodi od usluga')
                            ->prefix('KM')
                            ->default(0),
                        \App\Filament\Components\MoneyInput::make('income_other')
                            ->label('Naplaćeni ostali prihodi')
                            ->prefix('KM')
                            ->default(0),
                        \App\Filament\Components\MoneyInput::make('income_financial')
                            ->label('Naplaćeni finansijski prihodi')
                            ->prefix('KM')
                            ->default(0),
                    ])->columns(2),

                Section::make('Ukupno i PDV')
                    ->schema([
                        \App\Filament\Components\MoneyInput::make('total_income')
                            ->label('Ukupno naplaćeni prihodi')
                            ->prefix('KM')
                            ->required(),
                        \App\Filament\Components\MoneyInput::make('vat_amount')
                            ->label('Obračunati PDV')
                            ->prefix('KM')
                            ->default(0),
                    ])->columns(2),

                Section::make('Veze')
                    ->schema([
                        Select::make('payment_id')
                            ->label('Uplata')
                            ->relationship('payment', 'id')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Select::make('invoice_id')
                            ->label('Faktura')
                            ->relationship('invoice', 'invoice_number')
                            ->searchable()
                            ->preload(),
                    ])->columns(2),
            ]);
    }
}
