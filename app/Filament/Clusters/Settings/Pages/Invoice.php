<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Enums\InvoiceTemplateEnum;
use App\Enums\LanguageEnum;
use App\Filament\Clusters\Settings\SettingsCluster;
use App\Models\CompanyBankAccount;
use App\Models\CompanySetting;
use App\Models\Invoice as InvoiceModel;
use App\Services\DocumentNumberingService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\HtmlString;

class Invoice extends Page
{
    use InteractsWithForms;

    protected static ?string $cluster = SettingsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $navigationLabel = 'Invoice';

    protected static string | \UnitEnum | null $navigationGroup = 'Company Settings';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.settings';

    public string $invoice_pdf_filename_format;
    public string $default_invoice_template;
    public string $default_invoice_language;
    public int $invoice_due_date_days;
    public string $invoice_default_currency;

    public ?int $default_company_bank_account_id = null;
//    public string $company_name;
//    public string $company_address;
//    public string $company_email;
//    public string $company_phone;
//    public string $company_vat_id;

    public bool $invoice_numbering_reset_yearly;
    public int $invoice_numbering_pad_zeros;
    public int $invoice_numbering_starting_number;
    public string $invoice_numbering_prefix;

    public function mount(): void
    {
        $this->form->fill([
            'invoice_pdf_filename_format' => (string) CompanySetting::get('invoice_pdf_filename_format'),
            'default_invoice_template' => (string) CompanySetting::get('default_invoice_template', InvoiceTemplateEnum::Classic->value),
            'default_invoice_language' => (string) CompanySetting::get('default_invoice_language', LanguageEnum::English->value),
            'invoice_due_date_days' => (int) CompanySetting::get('default_invoice_due_days'),
            'invoice_default_currency' => (string) CompanySetting::get('default_invoice_currency'),
            'default_company_bank_account_id' => (int) CompanySetting::get('default_company_bank_account_id', 0) ?: null,
//            'company_name' => (string) CompanySetting::get('company_name'),
//            'company_address' => (string) CompanySetting::get('company_address'),
//            'company_email' => (string) CompanySetting::get('company_email'),
//            'company_phone' => (string) CompanySetting::get('company_phone'),
//            'company_vat_id' => (string) CompanySetting::get('company_vat_id'),

            'invoice_numbering_reset_yearly' => (bool) CompanySetting::get('invoice_numbering_reset_yearly', true),
            'invoice_numbering_pad_zeros' => (int) CompanySetting::get('invoice_numbering_pad_zeros', 3),
            'invoice_numbering_starting_number' => (int) CompanySetting::get('invoice_numbering_starting_number', 1),
            'invoice_numbering_prefix' => (string) CompanySetting::get('invoice_numbering_prefix', 'currency'),
        ]);
    }

    protected function getFormSchema(): array
    {
        $numbering = app(DocumentNumberingService::class);

        return [
            Section::make('Invoice General Settings')
                ->description('Configure your company details to be displayed on invoices.')
                ->schema([

                    //due date number of days

                    TextInput::make('invoice_due_date_days')
                        ->label('Due Date (in days)')
                        ->helperText('Number of days after invoice date when the invoice is due.')
                        ->numeric()
                        ->minValue(0)
                        ->required()
                        ->default(14)
                        ->columnSpanFull(),
//default currency
                    Select::make('invoice_default_currency')
                        ->label('Default Currency')
                        ->options(function () {
                            $company = filament()->getTenant();
                            if (! $company) {
                                return [];
                            }
                            return $company->currencies->pluck('name', 'code')->toArray();
                        })
                        ->preload()
                        ->required()
                        ->columnSpanFull(),
                    Select::make('default_company_bank_account_id')
                        ->label('Default bank account')
                        ->options(function () {
                            $tenantId = filament()->getTenant()?->id;

                            if (! $tenantId) {
                                return [];
                            }

                            return CompanyBankAccount::where('company_id', $tenantId)
                                ->orderByDesc('is_default')
                                ->orderBy('bank_name')
                                ->pluck('bank_name', 'id')
                                ->toArray();
                        })
                        ->searchable()
                        ->preload()
                        ->nullable()
                        ->columnSpanFull(),
                ])->columns(12),

            Section::make('Invoice numbering')
                ->description('Configure how invoice numbers are generated.')
                ->schema([
                    Toggle::make('invoice_numbering_reset_yearly')
                        ->label('Reset counter yearly')
                        ->helperText('If enabled, numbering resets each year for the selected prefix.')
                        ->live()
                        ->columnSpanFull(),

                    TextInput::make('invoice_numbering_prefix')
                        ->label('Prefix')
                        ->helperText('Use Currency, None, or enter custom text (e.g. INV)')
                        ->placeholder('currency | INV | empty')
                        ->columnSpan(6)
                        ->hintActions([
                            Action::make('none')
                                ->label('None')
                                ->action(fn (Set $set) => $set('invoice_numbering_prefix', 'none')),

                            Action::make('currency')
                                ->label('Currency')
                                ->action(fn (Set $set) => $set('invoice_numbering_prefix', 'currency')),

                            Action::make('inv')
                                ->label('F')
                                ->action(fn (Set $set) => $set('invoice_numbering_prefix', 'F')),

                            Action::make('inv')
                                ->label('INV')
                                ->action(fn (Set $set) => $set('invoice_numbering_prefix', 'INV')),
                        ]),

                    TextInput::make('invoice_numbering_pad_zeros')
                        ->label('Pad zeros')
                        ->numeric()
                        ->minValue(1)
                        ->required()
                        ->columnSpan(3),

                    TextInput::make('invoice_numbering_starting_number')
                        ->label('Starting number')
                        ->numeric()
                        ->minValue(1)
                        ->required()
                        ->columnSpan(3),

                    Section::make('Used numerations')
                        ->schema(function () {
                            $tenantId = filament()->getTenant()?->id;
                            if (! $tenantId) {
                                return [];
                            }

                            $currencyCodes = filament()->getTenant()?->currencies()->pluck('code')->map(fn ($c) => strtoupper((string) $c))->toArray() ?? [];

                            $rows = InvoiceModel::where('company_id', $tenantId)
                                ->selectRaw('invoice_prefix, invoice_year, MAX(CAST(invoice_number AS UNSIGNED)) as max_number, MAX(LENGTH(invoice_number)) as pad_length, COUNT(*) as invoices_count')
                                ->groupBy('invoice_prefix', 'invoice_year')
                                ->orderBy('invoice_prefix')
                                ->orderByDesc('invoice_year')
                                ->get();

                            $components = [];

                            foreach ($rows as $row) {
                                $prefix = $row->invoice_prefix !== null ? strtoupper((string) $row->invoice_prefix) : null;
                                $bucketYear = (int) $row->invoice_year;
                                $maxNumber = (int) $row->max_number;
                                $padLength = max(1, (int) $row->pad_length);
                                $count = (int) $row->invoices_count;

                                $labelPrefix = $prefix ?: '(no prefix)';
                                $key = preg_replace('/[^A-Za-z0-9_]/', '_', $labelPrefix . '_' . $bucketYear);
                                $isCurrency = $prefix && in_array($prefix, $currencyCodes, true);

                                $numberPart = str_pad((string) $maxNumber, $padLength, '0', STR_PAD_LEFT);
                                $display = match (true) {
                                    $bucketYear > 0 && $prefix => "{$prefix}-{$numberPart}/{$bucketYear}",
                                    $bucketYear > 0 && ! $prefix => "{$numberPart}/{$bucketYear}",
                                    $prefix => "{$prefix}-{$numberPart}",
                                    default => $numberPart,
                                };

                                $components[] = Placeholder::make('used_numeration_' . $key)
                                    ->label($bucketYear > 0 ? "{$labelPrefix} ({$bucketYear})" : "{$labelPrefix} (all years)")
                                    ->content(new HtmlString("{$display}<br><span class=\"text-xs text-gray-500\">Invoices: {$count}</span>"))
                                    ->hintAction(
                                        Action::make('use_numeration_' . $key)
                                            ->label('Use')
                                            ->action(function (Set $set) use ($isCurrency, $prefix, $bucketYear, $padLength, $maxNumber) {
                                                $set('invoice_numbering_reset_yearly', $bucketYear > 0);
                                                $set('invoice_numbering_pad_zeros', $padLength);
                                                $set('invoice_numbering_starting_number', max(1, $maxNumber + 1));
                                                $set('invoice_numbering_prefix', $isCurrency ? 'currency' : ($prefix ?: 'none'));

                                                Notification::make()
                                                    ->success()
                                                    ->title('Numeration selected')
                                                    ->send();
                                            })
                                    )
                                    ->columnSpan(12);
                            }

                            return $components;
                        })
                        ->columns(12)
                        ->columnSpanFull(),

                    Placeholder::make('invoice_numbering_preview')
                        ->label('Next invoice number')
                        ->content(fn () => $numbering->assign(tap(new InvoiceModel(), function (InvoiceModel $invoice) {
                            $invoice->company_id = Filament::getTenant()?->id;
                            $invoice->currency = null;
                            $invoice->date = now();
                        }), ['prefix' => 'invoice_prefix', 'year' => 'invoice_year', 'number' => 'invoice_number'], preview: true))
                        ->columnSpan(6),
                ])
                ->columns(12),

            Section::make('PDF Configuration')
                ->description('Configure the generated PDF invoice.')
                ->schema([
                    Select::make('default_invoice_template')
                        ->label('Default template')
                        ->options(collect(InvoiceTemplateEnum::cases())
                            ->mapWithKeys(fn (InvoiceTemplateEnum $t) => [$t->value => $t->getLabel()])
                            ->toArray())
                        ->required()
                        ->columnSpanFull(),

                    Select::make('default_invoice_language')
                         ->label('Default language')
                         ->options(collect(LanguageEnum::cases())
                             ->mapWithKeys(fn (LanguageEnum $l) => [$l->value => $l->getLabel()])
                             ->toArray())
                         ->required()
                         ->columnSpanFull(),

                    TextInput::make('invoice_pdf_filename_format')
                        ->label('PDF Filename Format')
                        ->helperText('Available placeholders: {{ number }}, {{ client }}')
                        ->placeholder('invoice_{{ number }}.pdf')
                        ->required()
                        ->columnSpanFull(),
                ])->columns(12),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        CompanySetting::set('default_invoice_template', $data['default_invoice_template'] ?? InvoiceTemplateEnum::Classic->value);
        CompanySetting::set('default_invoice_language', $data['default_invoice_language'] ?? LanguageEnum::English->value);
        CompanySetting::set('default_invoice_due_days', (int) ($data['invoice_due_date_days']));
        CompanySetting::set('default_invoice_currency', $data['invoice_default_currency']);
        CompanySetting::set('default_company_bank_account_id', (int) ($data['default_company_bank_account_id'] ?? 0));
        CompanySetting::set('invoice_pdf_filename_format', $data['invoice_pdf_filename_format'] ?? '');
//        CompanySetting::set('company_name', $data['company_name'] ?? '');
//        CompanySetting::set('company_address', $data['company_address'] ?? '');
//        CompanySetting::set('company_email', $data['company_email'] ?? '');
//        CompanySetting::set('company_phone', $data['company_phone'] ?? '');
//        CompanySetting::set('company_vat_id', $data['company_vat_id'] ?? '');

        CompanySetting::set('invoice_numbering_reset_yearly', (bool) ($data['invoice_numbering_reset_yearly'] ?? true));
        CompanySetting::set('invoice_numbering_pad_zeros', (int) ($data['invoice_numbering_pad_zeros'] ?? 3));
        CompanySetting::set('invoice_numbering_starting_number', (int) ($data['invoice_numbering_starting_number'] ?? 1));
        CompanySetting::set('invoice_numbering_prefix', (string) ($data['invoice_numbering_prefix'] ?? 'currency'));

        Notification::make()
            ->success()
            ->title('Settings saved successfully.')
            ->send();
    }
}
