<?php

namespace App\Filament\Resources\Companies;

use App\Models\Company;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
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
            ->schema([
                Section::make('Basic Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        DatePicker::make('subscription_ends_at')
                            ->label('Subscription Expires At')
                            ->native(false)
                            ->displayFormat('d.m.Y')
                            ->closeOnDateSelection(),
                    ])->columns(2),

                Section::make('SMTP Settings')
                    ->description('Configure custom email server. Leave blank to use system default.')
                    ->schema([
                        TextInput::make('smtp_host')
                            ->label('Host'),
                        TextInput::make('smtp_port')
                            ->label('Port'),
                        TextInput::make('smtp_username')
                            ->label('Username'),
                        TextInput::make('smtp_password')
                            ->label('Password')
                            ->password()
                            ->revealable(),
                        TextInput::make('smtp_encryption')
                            ->label('Encryption'),
                        TextInput::make('smtp_from_address')
                            ->label('From Email')
                            ->email(),
                        TextInput::make('smtp_from_name')
                            ->label('From Name'),
                    ])->columns(2)
                    ->collapsed(),
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
