<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages;
use App\Models\Company;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-office';
    protected static string | \UnitEnum | null $navigationGroup = 'Administration';

    protected static bool $isScopedToTenant = false;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                \Filament\Forms\Components\DatePicker::make('subscription_ends_at')
                    ->label('Subscription Expires At')
                    ->native(false)
                    ->displayFormat('d.m.Y')
                    ->closeOnDateSelection(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('subscription_ends_at')
                    ->label('Subscription')
                    ->date('d.m.Y')
                    ->sortable()
                    ->description(fn (Company $record) => $record->subscription_ends_at
                        ? ($record->subscription_ends_at->isPast()
                            ? 'Expired ' . $record->subscription_ends_at->diffForHumans()
                            : 'Expires ' . $record->subscription_ends_at->diffForHumans())
                        : 'No subscription set'
                    )
                    ->color(fn (Company $record) => $record->subscription_ends_at?->isPast() ? 'danger' : 'success'),
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
                DeleteAction::make(),
            ])
            ->bulkActions([
//                \Filament\Tables\Actions\BulkActionGroup::make([
//                    \Filament\Tables\Actions\DeleteBulkAction::make(),
//                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCompanies::route('/'),
        ];
    }
}
