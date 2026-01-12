<?php

namespace App\Filament\Resources\Proformas\Schemas;

use App\Enums\InvoiceStatusEnum;
use App\Enums\LanguageEnum;
use App\Filament\Components\MoneyInput;
use App\Models\Article;
use App\Models\CompanySetting;
use App\Models\Currency;
use App\Models\Proforma;
use App\Services\DocumentNumberingService;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class ProformaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(12)->schema([
                Grid::make(12)
                    ->schema([
                        Section::make('Predračun')
                            ->description(fn (Get $get) => app(DocumentNumberingService::class)->assign(
                                tap(new Proforma, function (Proforma $proforma) use ($get) {
                                    $proforma->company_id = Filament::getTenant()?->id;
                                    $proforma->currency = $get('currency');
                                    $proforma->date = $get('date') ?? now();
                                }),
                                ['prefix' => 'proforma_prefix', 'year' => 'proforma_year', 'number' => 'proforma_number'],
                                preview: true
                            ))
                            ->schema([
                                Select::make('client_id')
                                    ->relationship('client', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(6),

                                DatePicker::make('date')
                                    ->label('Datum')
                                    ->default(now())
                                    ->required()
                                    ->columnSpan(3),

                                DatePicker::make('due_date')
                                    ->label('Rok plaćanja')
                                    ->default(now()->addDays(CompanySetting::get('default_invoice_due_days')))
                                    ->columnSpan(3),
                            ])
                            ->columns(12)
                            ->columnSpanFull(),

                        Section::make('Items')
                            ->schema([
                                Repeater::make('items')
                                    ->hiddenLabel()
                                    ->compact()
                                    ->relationship()
                                    ->schema([
                                        Select::make('article_id')
                                            ->relationship('article', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                $article = Article::find($state);
                                                if (! $article) {
                                                    return;
                                                }

                                                $currency = $get('../../currency');

                                                $raw = $article->prices_meta[$currency]
                                                    ?? collect($article->prices_meta)->first()
                                                    ?? 0;

                                                $rawPrice = is_array($raw)
                                                    ? ($raw['price'] ?? 0)
                                                    : $raw;

                                                $price = (float) str_replace(',', '.', (string) $rawPrice);
                                                $qty = max(1, (int) ($get('quantity') ?? 1));

                                                $set('unit_price', number_format($price, 2, ',', '.'));
                                                $set('total', number_format($price * $qty, 2, ',', '.'));

                                                $set('name', $article->name);
                                                $set('description', $article->description);
                                            })
                                            ->columnSpan(5),

                                        Hidden::make('name'),
                                        Hidden::make('description'),

                                        TextInput::make('quantity')
                                            ->numeric()
                                            ->default(1)
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                $qty = max(1, (int) $state);

                                                $unitPriceState = $get('unit_price');
                                                $unit = is_int($unitPriceState)
                                                    ? $unitPriceState / 100
                                                    : (float) str_replace(',', '.', str_replace('.', '', (string) $unitPriceState));

                                                $set('total', number_format($unit * $qty, 2, ',', '.'));
                                            })
                                            ->columnSpan(2),

                                        MoneyInput::make('unit_price')
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                $qty = max(1, (int) ($get('quantity') ?? 1));
                                                $unit = is_int($state)
                                                    ? $state / 100
                                                    : (float) str_replace(',', '.', str_replace('.', '', (string) $state));

                                                $set('total', number_format($unit * $qty, 2, ',', '.'));
                                            })
                                            ->columnSpan(3),

                                        MoneyInput::make('total')
                                            ->disabled()
                                            ->dehydrated()
                                            ->columnSpan(2),
                                    ])
                                    ->columns(12),
                            ])
                            ->columnSpanFull(),

                        Section::make('Notes')
                            ->schema([
                                Textarea::make('notes')
                                    ->rows(2)
                                    ->columnSpanFull(),
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columnSpan(9),

                Section::make('Info')
                    ->schema([
                        Select::make('status')
                            ->options(InvoiceStatusEnum::class)
                            ->default(InvoiceStatusEnum::Draft)
                            ->required(),

                        Select::make('language')
                            ->options(
                                collect(LanguageEnum::cases())
                                    ->mapWithKeys(fn (LanguageEnum $lang) => [$lang->value => $lang->getLabel()])
                                    ->toArray()
                            )
                            ->default(CompanySetting::get('default_invoice_language'))
                            ->required(),

                        Select::make('currency')
                            ->options(function () {
                                $tenantId = Filament::getTenant()?->id;

                                return Currency::when($tenantId, fn ($q) => $q->where('company_id', $tenantId))
                                    ->orderBy('code')
                                    ->pluck('code', 'code')
                                    ->toArray();
                            })
                            ->required()
                            ->default(CompanySetting::get('default_invoice_currency')),

                        TextInput::make('proforma_prefix')
                            ->label('Prefix')
                            ->helperText('Prefix is configured via Invoice numbering settings (currency/none/custom).')
                            ->disabled()
                            ->dehydrated(false)
                            ->visible(false),
                    ])
                    ->columnSpan(3),
            ])->columnSpanFull(),
        ]);
    }
}
