<?php

namespace App\Filament\Resources\Invoices\Schemas;

use App\Filament\Resources\Proformas\ProformaResource;
use App\Filament\Resources\Quotes\QuoteResource;
use App\Models\Proforma;
use App\Models\Quote;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InvoiceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Invoice Details')
                    ->schema([
                        TextEntry::make('client.name')
                            ->label('Client'),
                        TextEntry::make('status')
                            ->badge(),
                        TextEntry::make('date')
                            ->date(),
                        TextEntry::make('due_date')
                            ->date(),
                        TextEntry::make('language'),
                        //                            ->formatStateUsing(fn (string $state): string => match ($state) {
                        //                                'en' => 'English',
                        //                                'sr' => 'Serbian (Latin)',
                        //                                default => $state,
                        //                            }),

                        TextEntry::make('source_quote')
                            ->label('Ponuda')
                            ->state(fn ($record) => $record->sourceable instanceof Quote ? $record->sourceable->formatted_number : null)
                            ->url(fn ($record) => $record->sourceable instanceof Quote
                                ? QuoteResource::getUrl('edit', ['record' => $record->sourceable])
                                : null)
                            ->openUrlInNewTab()
                            ->visible(fn ($record) => $record->sourceable instanceof Quote),

                        TextEntry::make('source_proforma')
                            ->label('Predračun')
                            ->state(fn ($record) => $record->sourceable instanceof Proforma ? $record->sourceable->formatted_number : null)
                            ->url(fn ($record) => $record->sourceable instanceof Proforma
                                ? ProformaResource::getUrl('edit', ['record' => $record->sourceable])
                                : null)
                            ->openUrlInNewTab()
                            ->visible(fn ($record) => $record->sourceable instanceof Proforma),

                    ])->columns(6)->columnSpanFull(),

                Section::make('Items')
                    ->schema([
                        RepeatableEntry::make('items')
                            ->schema([
                                TextEntry::make('description'),
                                TextEntry::make('quantity'),
                                TextEntry::make('unit_price')
                                    ->money('BAM', '100', 'sr'),
                                TextEntry::make('total')
                                    ->money('BAM', '100', 'sr'),
                            ])
                            ->columns(4),
                    ])->columnSpanFull(),

                Section::make('Totals')
                    ->schema([
                        TextEntry::make('subtotal')
                            ->money('BAM', '100', 'sr'),
                        TextEntry::make('tax')
                            ->money('BAM', '100', 'sr'),
                        TextEntry::make('total')
                            ->money('BAM', '100', 'sr')
                            ->weight('bold')
                            ->size('lg'),
                        TextEntry::make('amount_paid')
                            ->money('BAM', '100', 'sr'),
                    ])->columns(4)->columnSpanFull(),

                Section::make('Fiscal Data')
                    ->schema([
                        TextEntry::make('is_fiscalized')
                            ->label('Status')
                            ->badge()
                            ->color(fn (bool $state): string => $state ? 'success' : 'gray')
                            ->formatStateUsing(fn (bool $state): string => $state ? 'Fiskalizovan' : 'Nije fiskalizovan'),
                        TextEntry::make('fiscal_invoice_number')
                            ->label('Fiskalni Broj')
                            ->copyable()
                            ->visible(fn ($record) => $record->is_fiscalized),
                        TextEntry::make('fiscal_counter')
                            ->label('Brojač Računa')
                            ->visible(fn ($record) => $record->is_fiscalized),
                        TextEntry::make('fiscalized_at')
                            ->label('Fiskalizovano')
                            ->dateTime()
                            ->visible(fn ($record) => $record->is_fiscalized),
                        TextEntry::make('fiscal_verification_url')
                            ->label('URL za Verifikaciju')
                            ->url(fn ($state) => $state, true)
                            ->openUrlInNewTab()
                            ->limit(50)
                            ->visible(fn ($record) => $record->is_fiscalized)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Email History')
                    ->schema([
                        RepeatableEntry::make('emailLogs')
                            ->label('Sent Emails')
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label('Sent At')
                                    ->dateTime(),
                                TextEntry::make('opened_at')
                                    ->label('Opened At')
                                    ->dateTime()
                                    ->placeholder('Not opened yet'),
                                TextEntry::make('clicked_at')
                                    ->label('Clicked At')
                                    ->dateTime()
                                    ->placeholder('Not clicked yet'),
                            ])
                            ->columns(3),
                    ]),
            ]);
    }
}
