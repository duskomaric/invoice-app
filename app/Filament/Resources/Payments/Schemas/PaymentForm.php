<?php

namespace App\Filament\Resources\Payments\Schemas;

use App\Enums\PaymentTypeEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Payment Details')
                    ->schema([
                        Select::make('type')
                            ->label('Tip')
                            ->options(PaymentTypeEnum::class)
                            ->default(PaymentTypeEnum::INCOME)
                            ->required()
                            ->live(),
                        Select::make('payment_method')
                            ->label('Način plaćanja')
                            ->options([
                                'bank' => 'Banka',
                                'cash' => 'Blagajna',
                            ])
                            ->default('bank')
                            ->required(),
                        TextInput::make('document_number')
                            ->label('Broj dokumenta (izvoda/naloga)')
                            ->maxLength(255),
                        Select::make('client_id')
                            ->relationship('client', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(fn ($state, callable $set) => $set('client_id', $state)),
                        Placeholder::make('current_balance')
                            ->label('Current Balance')
                            ->content(function ($get, $record) {
                                $clientId = $get('client_id') ?? $record?->client_id;

                                if (! $clientId) {
                                    return 'Select a client first';
                                }

                                $client = \App\Models\Client::find($clientId);

                                if (! $client) {
                                    return 'N/A';
                                }

                                $totalInvoiced = $client->invoices()->get()->sum('total');
                                $totalPaid = $client->payments()->sum('amount');
                                $balance = $totalPaid - $totalInvoiced;

                                return number_format($balance / 100, 2, ',', '.').' KM';
                            }),
                        \App\Filament\Components\MoneyInput::make('amount')
                            ->required()
                            ->prefix('KM')
                            ->helperText('Use negative amount for initial debt'),
                        DatePicker::make('payment_date')
                            ->label('Datum uplate')
                            ->required()
                            ->default(now()),
                        Select::make('invoice_id')
                            ->label('Faktura')
                            ->relationship(
                                'invoice',
                                'invoice_number',
                                fn ($query, $get) => $query
                                    ->where('client_id', $get('client_id'))
                                    ->whereIn('status', ['sent', 'partial', 'overdue'])
                            )
                            ->searchable()
                            ->preload()
                            ->visible(fn ($get) => $get('type') === PaymentTypeEnum::INCOME->value)
                            ->helperText('Ako nije izabran dokument, primjenjuje se FIFO raspodjela'),
                        Select::make('quote_id')
                            ->label('Ponuda')
                            ->relationship(
                                'quote',
                                'quote_number',
                                fn ($query, $get) => $query
                                    ->where('client_id', $get('client_id'))
                            )
                            ->searchable()
                            ->preload()
                            ->visible(fn ($get) => $get('type') === PaymentTypeEnum::INCOME->value),
                        Select::make('proforma_id')
                            ->label('Predračun')
                            ->relationship(
                                'proforma',
                                'proforma_number',
                                fn ($query, $get) => $query
                                    ->where('client_id', $get('client_id'))
                            )
                            ->searchable()
                            ->preload()
                            ->visible(fn ($get) => $get('type') === PaymentTypeEnum::INCOME->value),
                        Textarea::make('notes')
                            ->label('Napomene')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}
