<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\EditTenantProfile;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EditCompanyProfile extends EditTenantProfile
{
    public static function getLabel(): string
    {
        return 'Company Profile';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Subscription Status')
                    ->schema([
                        \Filament\Forms\Components\Placeholder::make('subscription_info')
                            ->label('Valid Until')
                            ->content(fn ($record) => $record->subscription_ends_at
                                ? $record->subscription_ends_at->format('d.m.Y').
                                  ' ('.($record->subscription_ends_at->isPast() ? 'Expired' : $record->subscription_ends_at->diffForHumans()).')'
                                : 'Lifetime Subscription (Unlimited)'
                            )
                            ->columnSpanFull(),
                    ]),

                Section::make('Basic Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                    ])->columns(2),

                Section::make('Address & Contact')
                    ->schema([
                        TextInput::make('address')->maxLength(255),
                        TextInput::make('city')->maxLength(255),
                        TextInput::make('postal_code')->maxLength(20),
                        TextInput::make('country')->default('BiH')->maxLength(100),
                        TextInput::make('phone')->tel()->maxLength(50),
                        TextInput::make('email')->email()->maxLength(255),
                        TextInput::make('website')->url()->maxLength(255),
                    ])->columns(2),

                Section::make('Legal Information')
                    ->schema([
                        TextInput::make('identification_number')
                            ->label('JIB')
                            ->maxLength(20),
                        TextInput::make('vat_number')
                            ->label('VAT Number')
                            ->maxLength(20),
                    ])->columns(2),

                Section::make('Fiscalization (OFS)')
                    ->description('Configuration for Fiscalization Service')
                    ->schema([
                        TextInput::make('ofs_base_url')
                            ->label('Base URL')
                            ->default('https://pos.ofs.ba')
                            ->required(),
                        TextInput::make('ofs_api_key')
                            ->label('API Key')
                            ->password()
                            ->revealable(),
                        TextInput::make('ofs_serial_number')
                            ->label('Serial Number'),
                        TextInput::make('ofs_pac')
                            ->label('PAC Code'),
                    ])->columns(2),
            ]);
    }
}
