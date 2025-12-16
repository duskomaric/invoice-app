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
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            /* ROOT GRID */
            Grid::make(12)->schema([

                /* ================= LEFT SIDE (9) ================= */
                Grid::make(12)
                    ->schema([

                        /* BASIC INVOICE INFO */
                        Section::make('Invoice')
                            ->schema([
                                Select::make('client_id')
                                    ->relationship('client', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(6),

                                Select::make('status')
                                    ->options(InvoiceStatus::class)
                                    ->default(InvoiceStatus::Draft)
                                    ->required()
                                    ->columnSpan(3),

                                Select::make('language')
                                    ->options([
                                        'en' => 'English',
                                        'sr' => 'Serbian (Latin)',
                                    ])
                                    ->default('en')
                                    ->required()
                                    ->columnSpan(3),

                                DatePicker::make('date')
                                    ->default(now())
                                    ->required()
                                    ->columnSpan(3),

                                DatePicker::make('due_date')
                                    ->default(now()->addDays(30))
                                    ->columnSpan(3),

                                Select::make('currency')
                                    ->options(Setting::get('invoice_prefixes'))
                                    ->required()
                                    ->columnSpan(3),
                            ])
                            ->columns(12)
                            ->columnSpanFull(),

                        /* ITEMS */
                        Section::make('Items')
                            ->schema([
                                Repeater::make('items')
                                    ->relationship()
                                    ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                                        $data['total'] = ($data['quantity'] ?? 1) * ($data['unit_price'] ?? 0);
                                        return $data;
                                    })
                                    ->mutateRelationshipDataBeforeSaveUsing(function (array $data): array {
                                        $data['total'] = ($data['quantity'] ?? 1) * ($data['unit_price'] ?? 0);
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
                                                $price = (float) str_replace(',', '.', str_replace('.', '', $get('unit_price')));
                                                $set('total', $state * $price);
                                            })
                                            ->columnSpan(1),

                                        MoneyInput::make('unit_price')
                                            ->reactive()
                                            ->afterStateUpdated(fn ($state, Set $set, Get $get) =>
                                            $set('total',
                                                (float) str_replace(',', '.', str_replace('.', '', $state))
                                                * $get('quantity')
                                            )
                                            )
                                            ->columnSpan(2),

                                        MoneyInput::make('total')
                                            ->disabled()
                                            ->dehydrated()
                                            ->columnSpan(2),
                                    ])
                                    ->columns(12),
                            ])
                            ->columnSpanFull(),

                        /* NOTES */
                        Section::make('Notes')
                            ->schema([
                                Textarea::make('notes')
                                    ->rows(4)
                                    ->columnSpanFull(),
                            ])
                            ->columnSpanFull(),

                    ])
                    ->columnSpan(9),

                /* ================= RIGHT SIDE (3) ================= */
                Section::make('Recurring')
                    ->schema([
                        Toggle::make('is_recurring')
                            ->live(),

                        Select::make('frequency')
                            ->options(InvoiceFrequency::class)
                            ->visible(fn (Get $get) => $get('is_recurring'))
                            ->required(fn (Get $get) => $get('is_recurring')),
                    ])
                    ->columnSpan(3),

            ])->columnSpanFull(),

        ]);
    }
}
