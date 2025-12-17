<?php

namespace App\Filament\Clusters\Settings\Resources\Currencies\Schemas;

use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Unique;

class CurrencyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('code')
                    ->required()
                    ->maxLength(3)
                    ->unique(modifyRuleUsing: fn (Unique $rule) => $rule->where('company_id', Filament::getTenant()?->id)),
                TextInput::make('prefix')
                    ->label('Prefix')
                    ->maxLength(10),
                TextInput::make('name')->required(),

            ]);
    }
}
