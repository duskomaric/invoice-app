<?php

namespace App\Filament\Resources\Invoices\Schemas;

use App\Enums\InvoiceTemplateEnum;
use App\Enums\InvoiceFrequencyEnum;
use App\Enums\InvoiceStatusEnum;
use App\Enums\LanguageEnum;
use App\Filament\Components\MoneyInput;
use App\Models\Article;
use App\Models\CompanyBankAccount;
use App\Models\CompanySetting;
use App\Models\Currency;
use App\Models\Invoice;
use App\Services\DocumentNumberingService;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
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
                            ->description(fn (Get $get) => app(DocumentNumberingService::class)->assign(
                                tap(new Invoice(), function (Invoice $invoice) use ($get) {
                                    $invoice->company_id = Filament::getTenant()?->id;
                                    $invoice->currency = $get('currency');
                                    $invoice->date = $get('date') ?? now();
                                }),
                                ['prefix' => 'invoice_prefix', 'year' => 'invoice_year', 'number' => 'invoice_number'],
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
                                    ->default(now())
                                    ->required()
                                    ->columnSpan(3),

                                DatePicker::make('due_date')
                                    ->default(now()->addDays(CompanySetting::get('default_invoice_due_days')))
                                    ->columnSpan(3),
                            ])
                            ->columns(12)
                            ->columnSpanFull(),

                        /* ITEMS */
                        Section::make('Items')
                            ->schema([
                                Repeater::make('items')
                                    ->hiddenLabel()
                                    ->compact()
                                    ->relationship()
                                    ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                                        return $data;
                                    })
                                    ->mutateRelationshipDataBeforeSaveUsing(function (array $data): array {
                                        return $data;
                                    })
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
                                            ->dehydrated() // stored as cents
                                            ->columnSpan(2),
                                    ])
                                    ->columns(12),
                            ])
                            ->columnSpanFull(),

                        /* NOTES */
                        Section::make('Notes')
                            ->schema([
                                Textarea::make('notes')
                                    ->rows(2)
                                    ->columnSpanFull(),
                            ])
                            ->columnSpanFull(),

                    ])
                    ->columnSpan(9),

                /* ================= RIGHT SIDE (3) ================= */
                Section::make('Info')
                    ->schema([
                        Select::make('status')
                            ->options(InvoiceStatusEnum::class)
                            ->default(InvoiceStatusEnum::Draft)
                            ->required(),

                        Select::make('bankAccounts')
                            ->label('Bank accounts')
                            ->relationship(
                                name: 'bankAccounts',
                                titleAttribute: 'bank_name',
                                modifyQueryUsing: fn ($query) => $query->where('company_id', Filament::getTenant()?->id)
                            )
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->default(function () {
                                $defaultId = (int) CompanySetting::get('default_company_bank_account_id', 0);

                                return $defaultId > 0 ? [$defaultId] : [];
                            }),

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

                        Select::make('invoice_template')
                            ->label('Template')
                            ->options(collect(InvoiceTemplateEnum::cases())
                                ->mapWithKeys(fn (InvoiceTemplateEnum $t) => [$t->value => $t->getLabel()])
                                ->toArray())
                            ->default(fn () => CompanySetting::get('default_invoice_template', InvoiceTemplateEnum::Classic->value))
                            ->required(),

                        Toggle::make('is_recurring')
                            ->live(),

                        Select::make('frequency')
                            ->options(InvoiceFrequencyEnum::class)
                            ->visible(fn (Get $get) => $get('is_recurring'))
                            ->required(fn (Get $get) => $get('is_recurring')),
                    ])
                    ->columnSpan(3),

            ])->columnSpanFull(),

        ]);
    }
}
