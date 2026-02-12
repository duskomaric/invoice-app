<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Settings\Resources\CompanyBankAccounts\Schemas;

use Filament\Facades\Filament;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CompanyBankAccountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('company_id')
                    ->default(fn () => Filament::getTenant()?->id)
                    ->required()
                    ->dehydrated(),

                TextInput::make('bank_name')
                    ->label('Bank name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('account_number')
                    ->label('Account / IBAN')
                    ->required()
                    ->maxLength(255),

                TextInput::make('swift')
                    ->label('SWIFT')
                    ->maxLength(255),

                Toggle::make('is_default')
                    ->label('Default')
                    ->default(false),
            ]);
    }
}
