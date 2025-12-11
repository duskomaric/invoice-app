<?php

namespace App\Filament\Resources\Clients\Tables;

use App\Models\Client;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ClientTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('phone')
                    ->searchable(),
                TextColumn::make('city')
                    ->searchable(),
                TextColumn::make('balance')
                    ->label('Balance')
                    ->money('BAM', '100', 'sr')
                    ->state(function (Client $record): float {
                        // Invert the sign: Paid - Invoiced
                        // If result is negative, they owe us (Red)
                        // If result is positive, they overpaid (Yellow)
                        return -$record->balance;
                    })
                    ->color(fn (string $state): string => match (true) {
                        (float) $state < 0 => 'danger', // Debt
                        (float) $state > 0 => 'warning', // Credit
                        default => 'success', // Zero
                    }),
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
