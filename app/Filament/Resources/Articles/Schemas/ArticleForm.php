<?php

namespace App\Filament\Resources\Articles\Schemas;

use App\Filament\Components\MoneyInput;
use App\Models\Setting;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
//        dd(Setting::get('ofs_tax_categories'));
        return $schema
            ->components([
                Section::make('Article Details')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Select::make('tax_category')
                            ->options(
                                collect(Setting::get('ofs_tax_categories'))
                                    ->mapWithKeys(fn($item) => [$item['label'] => $item['label']. ' ' . $item['rate']])
                                    ->toArray()
                            )
                            ->required(),
                        //unit
                        Select::make('unit')
                            ->options([
                                'KOM' => 'KOM',
                                'SAT' => 'SAT',
                            ])
                            ->required(),
                        MoneyInput::make('price')
                            ->required()
                            ->prefix('KM'),
                        Toggle::make('is_active')
                            ->required()
                            ->default(true),
                        Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}
