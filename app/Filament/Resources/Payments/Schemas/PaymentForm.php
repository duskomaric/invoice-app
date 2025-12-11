<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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

                                if (!$clientId) {
                                    return 'Select a client first';
                                }

                                $client = \App\Models\Client::find($clientId);

                                if (!$client) {
                                    return 'N/A';
                                }

                                $totalInvoiced = $client->invoices()->sum('total');
                                $totalPaid = $client->payments()->sum('amount');
                                $balance = $totalPaid - $totalInvoiced;

                                return number_format($balance / 100, 2, ',', '.') . ' KM';
                            }),
                        \App\Filament\Components\MoneyInput::make('amount')
                            ->required()
                            ->prefix('KM')
                            ->helperText('Use negative amount for initial debt'),
                        DatePicker::make('payment_date')
                            ->required()
                            ->default(now()),
                        Textarea::make('notes')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}
