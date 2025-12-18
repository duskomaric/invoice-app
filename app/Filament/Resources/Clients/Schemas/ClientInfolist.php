<?php

namespace App\Filament\Resources\Clients\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ClientInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Client Details')
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('email'),
                        TextEntry::make('phone'),
                        TextEntry::make('address'),
                        IconEntry::make('is_active')->label('Active')->boolean(),
                        TextEntry::make('city'),
                        TextEntry::make('zip'),
                        TextEntry::make('country'),
                        TextEntry::make('tax_id'),
                        TextEntry::make('vat_id'),
                    ])->columns(2)->columnSpanFull(),
            ]);
    }
}
