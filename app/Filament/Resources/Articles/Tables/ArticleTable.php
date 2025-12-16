<?php

namespace App\Filament\Resources\Articles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class ArticleTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                // Dynamic currency columns from prices_meta
                ...collect(Filament::getTenant()->currencies()->pluck('code'))
                    ->map(function ($currency) {
                        return TextColumn::make("prices_meta.$currency")
                        ->label("Price ($currency)")
                            ->getStateUsing(function ($record) use ($currency) {
                                // Get value if exists, otherwise null
                                return $record->prices_meta[$currency] ?? null;
                            })
                            ->formatStateUsing(fn ($state) =>
                            $state !== null
                                ? number_format((float) $state, 2, '.', '')
                                : '—'
                            )
                            ->toggleable();
                    })->toArray(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
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
