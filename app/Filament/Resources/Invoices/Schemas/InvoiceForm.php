<?php

namespace App\Filament\Resources\Invoices\Schemas;

use App\Enums\InvoiceFrequency;
use App\Enums\InvoiceStatus;
use App\Filament\Components\MoneyInput;
use App\Models\Article;
use App\Models\Setting;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([
                Section::make()
                    ->schema([
                        Select::make('client_id')
                            ->relationship('client', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Select::make('status')
                            ->options(InvoiceStatus::class)
                            ->required()
                            ->default(InvoiceStatus::Draft),
                        Select::make('language')
                            ->options([
                                'en' => 'English',
                                'sr' => 'Serbian (Latin)',
                            ])
                            ->required()
                            ->default('en'),
                        DatePicker::make('date')
                            ->required()
                            ->default(now()),
                        DatePicker::make('due_date')
                            ->default(now()->addDays(30)),

                        //currency is set in the URL and passed to the form
                        Select::make('currency')
                            ->options(
                                Setting::get('invoice_prefixes'),
                            )
                            ->required(),


                    ])->columnSpan(10)->columns(2),

                Section::make('Recurring')
                    ->schema([
                        Toggle::make('is_recurring')
                            ->live(),
                        Select::make('frequency')
                            ->options(InvoiceFrequency::class)
                            ->visible(fn (Get $get) => $get('is_recurring'))
                            ->required(fn (Get $get) => $get('is_recurring')),
                    ])->columnSpan(2)->columns(2),

                Section::make('Items')
                    ->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                                $data['total'] = ($data['quantity'] ?? 1) * ($data['unit_price'] ?? 0);
//                                $data['description'] = $data['description'] ?? '';
                                return $data;
                            })
                            ->mutateRelationshipDataBeforeSaveUsing(function (array $data): array {
                                $data['total'] = ($data['quantity'] ?? 1) * ($data['unit_price'] ?? 0);
//                                $data['description'] = $data['description'] ?? '';
                                return $data;
                            })
                            ->schema([
                                Select::make('article_id')
                                    ->relationship('article', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, Set $set) {
                                        $article = Article::find($state);
                                        if ($article) {
                                            $set('unit_price', $article->price / 100);
                                            $set('name', $article->name);
                                            $set('description', $article->description);
                                        }
                                    })
                                ->columnSpan(4),
                                TextInput::make('name')
                                    ->required()
                                    ->columnSpan(3),
                                Hidden::make('description'),
                                TextInput::make('quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        $unitPrice = $get('unit_price');
                                        // Parse unit price from "1.234,56" to float
                                        $parsedPrice = (float) str_replace(',', '.', str_replace('.', '', $unitPrice));
                                        $set('total', $state * $parsedPrice);
                                    })
                                    ->columnSpan(1),
                                MoneyInput::make('unit_price')
                                    ->columnSpan(2)
                                    ->reactive()
                                    ->afterStateUpdated(fn ($state, Set $set, Get $get) => $set('total', (float) str_replace(',', '.', str_replace('.', '', $state)) * $get('quantity'))),
                                MoneyInput::make('total')
                                    ->columnSpan(2)
                                    ->disabled()
                                    ->dehydrated(),
                            ])
                            ->columns(12)
                    ])->columnSpan(12),

                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
