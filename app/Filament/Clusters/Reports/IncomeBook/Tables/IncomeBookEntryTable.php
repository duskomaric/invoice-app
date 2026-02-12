<?php

namespace App\Filament\Clusters\Reports\IncomeBook\Tables;

use App\Enums\ReviewStatusEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class IncomeBookEntryTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Red. br.')
                    ->sortable(),
                TextColumn::make('booking_date')
                    ->label('Datum knjiženja')
                    ->date()
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Opis')
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('income_products')
                    ->label('Proizvodi')
                    ->money('BAM', '100', 'sr')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('income_goods')
                    ->label('Roba')
                    ->money('BAM', '100', 'sr')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('income_services')
                    ->label('Usluge')
                    ->money('BAM', '100', 'sr')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('income_other')
                    ->label('Ostalo')
                    ->money('BAM', '100', 'sr')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('income_financial')
                    ->label('Finansijski')
                    ->money('BAM', '100', 'sr')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('total_income')
                    ->label('Ukupno prihodi')
                    ->money('BAM', '100', 'sr')
                    ->sortable(),
                TextColumn::make('vat_amount')
                    ->label('PDV')
                    ->money('BAM', '100', 'sr')
                    ->sortable(),
                TextColumn::make('review_status')
                    ->label('Status pregleda')
                    ->badge()
                    ->sortable(),
                TextColumn::make('invoice.formatted_number')
                    ->label('Faktura')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('quote.formatted_number')
                    ->label('Ponuda')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('proforma.formatted_number')
                    ->label('Predračun')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('review_status')
                    ->label('Status pregleda')
                    ->options(ReviewStatusEnum::class)
                    ->multiple(),
                SelectFilter::make('invoice_id')
                    ->label('Faktura')
                    ->relationship('invoice', 'invoice_number')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('quote_id')
                    ->label('Ponuda')
                    ->relationship('quote', 'quote_number')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('proforma_id')
                    ->label('Predračun')
                    ->relationship('proforma', 'proforma_number')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('booking_date', 'desc');
    }
}
