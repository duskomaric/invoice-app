<?php

namespace App\Filament\Resources\Articles\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ArticleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Article Details')
                    ->schema([
                        TextEntry::make('description'),
                        TextEntry::make('price')
                            ->money('BAM', '100', 'sr'),
                    ])->columns(2),
            ]);
    }
}
