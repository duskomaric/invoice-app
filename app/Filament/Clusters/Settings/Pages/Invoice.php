<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Enums\InvoiceTemplate;
use App\Enums\LanguageEnum;
use App\Filament\Clusters\Settings\SettingsCluster;
use App\Models\CompanyBankAccount;
use App\Models\CompanySetting;
use App\Services\InvoiceNumberingService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;

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

    public string $invoice_numbering_format;
    public int $invoice_numbering_pad_length;
    public int $invoice_numbering_start_number;

    public function mount(): void
    {
        $this->form->fill([
            'invoice_pdf_filename_format' => (string) CompanySetting::get('invoice_pdf_filename_format'),
            'default_invoice_template' => (string) CompanySetting::get('default_invoice_template', InvoiceTemplate::Classic->value),
            'default_invoice_language' => (string) CompanySetting::get('default_invoice_language', LanguageEnum::English->value),
            'invoice_due_date_days' => (int) CompanySetting::get('default_invoice_due_days'),
            'invoice_default_currency' => (string) CompanySetting::get('default_invoice_currency'),
            'default_company_bank_account_id' => (int) CompanySetting::get('default_company_bank_account_id', 0) ?: null,
//            'company_name' => (string) CompanySetting::get('company_name'),
//            'company_address' => (string) CompanySetting::get('company_address'),
//            'company_email' => (string) CompanySetting::get('company_email'),
//            'company_phone' => (string) CompanySetting::get('company_phone'),
//            'company_vat_id' => (string) CompanySetting::get('company_vat_id'),

            'invoice_numbering_format' => (string) CompanySetting::get('invoice_numbering_format', '{prefix}-{number}/{year}'),
            'invoice_numbering_pad_length' => (int) CompanySetting::get('invoice_numbering_pad_length', 3),
            'invoice_numbering_start_number' => (int) CompanySetting::get('invoice_numbering_start_number', 1),
        ]);
    }

    protected function getFormSchema(): array
    {
        $numbering = app(InvoiceNumberingService::class);

        $presets = [
            '{currency}-{number}/{year}',
            '{prefix}/{year}/{number}',
            '{year}-{month}-{number}',
            '{prefix}/{year}/{month}/{number}',
            'INV-{number}/{year}',
        ];

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

            Section::make('Numbering Rules')
                ->description('Configure how invoice numbers are generated.')
                ->schema([
                    TextInput::make('invoice_numbering_format')
                        ->label('Number Format')
                        ->helperText('Placeholders: {prefix}, {currency}, {number}, {year}, {month}, {day}. You can also include static text, e.g. INV-{number}/{year}.')
                        ->required()
                        ->live()
                        ->columnSpanFull()
                        ->hintActions([
                            Action::make('presets')
                                ->label('Presets')
                                ->icon('heroicon-o-squares-2x2')
                                ->modalWidth(Width::Small)
                                ->modalSubmitAction(false)
                                ->modalCancelActionLabel('Close')
                                ->schema([
                                    Grid::make(1)->schema([
                                        Placeholder::make('p1')
                                            ->label($presets[0])
                                            ->content(fn () => $numbering->previewForConfig(
                                                format: $presets[0],
                                                padLength: (int) ($this->form->getState()['invoice_numbering_pad_length'] ?? 3),
                                                startingNumber: (int) ($this->form->getState()['invoice_numbering_start_number'] ?? 1),
                                                currency: 'EUR',
                                                date: now(),
                                            ))
                                            ->extraAttributes(['class' => 'text-sm text-gray-500'])
                                            ->hintAction(
                                                Action::make('use1')
                                                    ->label('Use')
                                                    ->action(fn () => $this->form->fill([
                                                        'invoice_numbering_format' => $presets[0],
                                                    ]))
                                            ),
                                        Placeholder::make('p2')
                                            ->label($presets[1])
                                            ->content(fn () => $numbering->previewForConfig(
                                                format: $presets[1],
                                                padLength: (int) ($this->form->getState()['invoice_numbering_pad_length'] ?? 3),
                                                startingNumber: (int) ($this->form->getState()['invoice_numbering_start_number'] ?? 1),
                                                currency: 'EUR',
                                                date: now(),
                                            ))
                                            ->hintAction(
                                                Action::make('use2')
                                                    ->label('Use')
                                                    ->action(fn () => $this->form->fill([
                                                        'invoice_numbering_format' => $presets[1],
                                                    ]))
                                            ),

                                        Placeholder::make('p3')
                                            ->label($presets[2])
                                            ->content(fn () => $numbering->previewForConfig(
                                                format: $presets[2],
                                                padLength: (int) ($this->form->getState()['invoice_numbering_pad_length'] ?? 3),
                                                startingNumber: (int) ($this->form->getState()['invoice_numbering_start_number'] ?? 1),
                                                currency: 'EUR',
                                                date: now(),
                                            ))
                                            ->hintAction(
                                                Action::make('use3')
                                                    ->label('Use')
                                                    ->action(fn () => $this->form->fill([
                                                        'invoice_numbering_format' => $presets[2],
                                                    ]))
                                            ),

                                        Placeholder::make('p4')
                                            ->label($presets[3])
                                            ->content(fn () => $numbering->previewForConfig(
                                                format: $presets[3],
                                                padLength: (int) ($this->form->getState()['invoice_numbering_pad_length'] ?? 3),
                                                startingNumber: (int) ($this->form->getState()['invoice_numbering_start_number'] ?? 1),
                                                currency: 'EUR',
                                                date: now(),
                                            ))
                                            ->hintAction(
                                                Action::make('use4')
                                                    ->label('Use')
                                                    ->action(fn () => $this->form->fill([
                                                        'invoice_numbering_format' => $presets[3],
                                                    ]))
                                            ),

                                        Placeholder::make('p5')
                                            ->label($presets[4])
                                            ->content(fn () => $numbering->previewForConfig(
                                                format: $presets[4],
                                                padLength: (int) ($this->form->getState()['invoice_numbering_pad_length'] ?? 3),
                                                startingNumber: (int) ($this->form->getState()['invoice_numbering_start_number'] ?? 1),
                                                currency: 'EUR',
                                                date: now(),
                                            ))
                                            ->hintAction(
                                                Action::make('use5')
                                                    ->label('Use')
                                                    ->action(fn () => $this->form->fill([
                                                        'invoice_numbering_format' => $presets[4],
                                                    ]))
                                            ),
                                    ]),
                                ]),
                        ]),

                    TextInput::make('invoice_numbering_pad_length')
                        ->label('Pad Zeros')
                        ->numeric()
                        ->minValue(1)
                        ->required()
                        ->live()
                        ->columnSpan(6),

                    TextInput::make('invoice_numbering_start_number')
                        ->label('Starting Number')
                        ->numeric()
                        ->minValue(1)
                        ->required()
                        ->live()
                        ->columnSpan(6),

                    Placeholder::make('invoice_numbering_preview')
                        ->label('Next Invoice Number')
                        ->content(fn (Get $get) => $numbering->previewForConfig(
                            format: (string) $get('invoice_numbering_format'),
                            padLength: (int) $get('invoice_numbering_pad_length'),
                            startingNumber: (int) $get('invoice_numbering_start_number'),
                            currency: 'EUR',
                            date: now(),
                        ))
                        ->columnSpanFull(),
                ])
                ->columns(12),

            Section::make('PDF Configuration')
                ->description('Configure the generated PDF invoice.')
                ->schema([
                    Select::make('default_invoice_template')
                        ->label('Default template')
                        ->options(collect(InvoiceTemplate::cases())
                            ->mapWithKeys(fn (InvoiceTemplate $t) => [$t->value => $t->getLabel()])
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

        CompanySetting::set('default_invoice_template', $data['default_invoice_template'] ?? InvoiceTemplate::Classic->value);
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

        CompanySetting::set('invoice_numbering_format', $data['invoice_numbering_format'] ?? '{prefix}-{number}/{year}');
        CompanySetting::set('invoice_numbering_pad_length', (int) ($data['invoice_numbering_pad_length'] ?? 3));
        CompanySetting::set('invoice_numbering_start_number', (int) ($data['invoice_numbering_start_number'] ?? 1));

        Notification::make()
            ->success()
            ->title('Settings saved successfully.')
            ->send();
    }
}
