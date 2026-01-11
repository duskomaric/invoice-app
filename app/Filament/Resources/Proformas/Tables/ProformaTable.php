<?php

namespace App\Filament\Resources\Proformas\Tables;

use App\Enums\InvoiceStatusEnum;
use App\Models\CompanySetting;
use App\Models\Proforma;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProformaTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('proforma_number')
                    ->label('Predračun #')
                    ->getStateUsing(fn (Proforma $proforma) => $proforma->formatted_number)
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight(FontWeight::Bold),
                TextColumn::make('client.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('currency')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'EUR' => 'warning',
                        'BAM' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('due_date')
                    ->label('Rok plaćanja')
                    ->date()
                    ->sortable(),
                TextColumn::make('total')
                    ->money(fn ($record) => $record->currency, 100)
                    ->sortable(),
                TextColumn::make('sourceQuote.formatted_number')
                    ->label('Iz ponude')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->defaultPaginationPageOption(CompanySetting::get('default_pagination_option'))
            ->filters([
                SelectFilter::make('status')
                    ->options(InvoiceStatusEnum::class),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
