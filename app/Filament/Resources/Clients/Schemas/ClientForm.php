<?php

namespace App\Filament\Resources\Clients\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            /* ROOT GRID */
            Grid::make(12)->schema([

                /* ================= LEFT SIDE (9) ================= */
                Grid::make(12)
                    ->schema([

                        /* BASIC INFO */
                        Section::make('Basic Information')
                            ->icon('heroicon-o-user')
                            ->description('General client details')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Client name')
                                    ->required()
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-building-office')
                                    ->columnSpan(6),

                                TextInput::make('email')
                                    ->email()
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-envelope')
                                    ->columnSpan(3),

                                TextInput::make('phone')
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-phone')
                                    ->columnSpan(3),
                            ])
                            ->columns(12)
                            ->columnSpanFull(),

                        /* ADDRESS */
                        Section::make('Address')
                            ->icon('heroicon-o-map-pin')
                            ->schema([
                                TextInput::make('address')
                                    ->maxLength(200)
                                    ->prefixIcon('heroicon-o-home')
                                    ->columnSpanFull(),

                                TextInput::make('city')
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-building-library')
                                    ->columnSpan(4),

                                TextInput::make('zip')
                                    ->label('ZIP / Postal code')
                                    ->maxLength(10)
                                    ->prefixIcon('heroicon-o-hashtag')
                                    ->columnSpan(4),

                                Select::make('country')
                                    ->options([
                                        'BA' => 'Bosnia and Herzegovina',
                                    ])
                                    ->default('BA')
                                    ->prefixIcon('heroicon-o-flag')
                                    ->columnSpan(4),
                            ])
                            ->columns(12)
                            ->columnSpanFull(),

                        /* TAX INFO */
                        Section::make('Tax Information')
                            ->icon('heroicon-o-receipt-percent')
                            ->description('Fiscal & VAT data (BiH)')
                            ->schema([
                                TextInput::make('tax_id')
                                    ->label('Tax ID (JIB)')
                                    ->helperText('Required for legal entities')
                                    ->maxLength(20)
                                    ->prefixIcon('heroicon-o-identification')
                                    ->columnSpan(6),

                                TextInput::make('vat_id')
                                    ->label('VAT ID (PIB)')
                                    ->helperText('Only if client is VAT registered')
                                    ->maxLength(20)
                                    ->prefixIcon('heroicon-o-banknotes')
                                    ->columnSpan(6),
                            ])
                            ->columns(12)
                            ->columnSpanFull(),

                    ])
                    ->columnSpan(9),

                /* ================= RIGHT SIDE (3) ================= */
                Section::make('Status')
                    ->icon('heroicon-o-check-circle')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Active')
                            ->helperText('Inactive clients cannot be selected on invoices')
                            ->default(true),
                    ])
                    ->columnSpan(3),

            ])->columnSpanFull(),

        ]);
    }
}
