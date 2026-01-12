<?php

namespace App\Filament\Resources\Payments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PaymentTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->label('Tip')
                    ->badge()
                    ->sortable(),
                TextColumn::make('payment_method')
                    ->label('Način plaćanja')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'bank' => 'Banka',
                        'cash' => 'Blagajna',
                        default => $state
                    })
                    ->sortable(),
                TextColumn::make('document_number')
                    ->label('Br. dokumenta')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('client.name')
                    ->label('Klijent')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('amount')
                    ->label('Iznos')
                    ->money('BAM', '100', 'sr')
                    ->sortable(),
                TextColumn::make('payment_date')
                    ->label('Datum')
                    ->date()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
