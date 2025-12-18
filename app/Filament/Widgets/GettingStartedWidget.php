<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Filament\Clusters\Settings\Resources\Currencies\Schemas\CurrencyForm;
use App\Models\Currency;
use App\Models\CompanySetting;
use Filament\Facades\Filament;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Filament\Widgets\Widget;
use Filament\Support\Icons\Heroicon;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class GettingStartedWidget extends Widget implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.widgets.getting-started-widget';

    protected int | string | array $columnSpan = 'full';

    public ?array $data = [];

    public function mount(): void
    {
        $tenantId = Filament::getTenant()?->id;

        if (! $tenantId) {
            return;
        }

        $this->form->fill([
            'currencies' => [
                [
                    'code' => null,
                    'prefix' => null,
                    'name' => null,
                ],
            ],

            'invoice_numbering_reset_yearly' => (bool) CompanySetting::get('invoice_numbering_reset_yearly', true),
            'invoice_numbering_pad_zeros' => (int) CompanySetting::get('invoice_numbering_pad_zeros', 3),
            'invoice_numbering_starting_number' => (int) CompanySetting::get('invoice_numbering_starting_number', 1),
            'invoice_numbering_prefix' => (string) CompanySetting::get('invoice_numbering_prefix', 'currency'),

            'company_name' => (string) CompanySetting::get('company_name'),
            'company_address' => (string) CompanySetting::get('company_address'),
            'invoice_pdf_filename_format' => (string) CompanySetting::get('invoice_pdf_filename_format'),
        ]);
    }

    public static function canView(): bool
    {
        $tenantId = Filament::getTenant()?->id;

        if (! $tenantId) {
            return true;
        }

        return ! self::isCompleted($tenantId);
    }

    public function form(Schema $schema): Schema
    {
        $tenantId = Filament::getTenant()?->id;

        return $schema
            ->statePath('data')
            ->components([
                Wizard::make([
                    Step::make('Currencies')
                        ->icon(Heroicon::OutlinedDocumentCurrencyEuro)
                        ->completedIcon(Heroicon::OutlinedCheckCircle)
                        ->description('Add at least one currency to start creating invoices.')
                        ->schema([
                            Repeater::make('currencies')
                                ->defaultItems(1)
                                ->reorderable(false)
                                ->schema([
                                    ...CurrencyForm::getComponents(),
                                ]),
                        ])
                        ->afterValidation(function () use ($tenantId) {
                            if (! $tenantId) {
                                return;
                            }

                            $data = $this->form->getState();

                            $rows = $data['currencies'] ?? [];
                            if (! is_array($rows)) {
                                $rows = [];
                            }

                            foreach ($rows as $row) {
                                if (! is_array($row)) {
                                    continue;
                                }

                                $code = strtoupper((string) ($row['code'] ?? ''));
                                $name = (string) ($row['name'] ?? '');

                                if ($code === '' || $name === '') {
                                    continue;
                                }

                                Currency::create([
                                    'company_id' => $tenantId,
                                    'code' => $code,
                                    'prefix' => ($row['prefix'] ?? null) ?: null,
                                    'name' => $name,
                                ]);
                            }

                            $this->form->fill([
                                ...$this->form->getState(),
                                'currencies' => [
                                    [
                                        'code' => null,
                                        'prefix' => null,
                                        'name' => null,
                                    ],
                                ],
                            ]);
                        }),

                    Step::make('Invoice numbering')
                        ->icon(Heroicon::OutlinedHashtag)
                        ->completedIcon(Heroicon::OutlinedCheckCircle)
                        ->description('Configure how invoice numbers are generated.')
                        ->schema([
                            Toggle::make('invoice_numbering_reset_yearly')
                                ->label('Reset counter yearly')
                                ->columnSpanFull(),

                            TextInput::make('invoice_numbering_prefix')
                                ->label('Prefix')
                                ->helperText("Use 'currency' to use invoice currency as prefix, or enter static text like INV")
                                ->required()
                                ->columnSpan(6),

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
                        ])
                        ->columns(12)
                        ->afterValidation(function () {
                            $data = $this->form->getState();

                            CompanySetting::set('invoice_numbering_reset_yearly', (bool) ($data['invoice_numbering_reset_yearly'] ?? true));
                            CompanySetting::set('invoice_numbering_pad_zeros', (int) ($data['invoice_numbering_pad_zeros'] ?? 3));
                            CompanySetting::set('invoice_numbering_starting_number', (int) ($data['invoice_numbering_starting_number'] ?? 1));
                            CompanySetting::set('invoice_numbering_prefix', (string) ($data['invoice_numbering_prefix'] ?? 'currency'));
                        }),

                    Step::make('Company invoice details')
                        ->icon(Heroicon::OutlinedBuildingOffice2)
                        ->completedIcon(Heroicon::OutlinedCheckCircle)
                        ->description('Set company info and PDF filename format used for invoices.')
                        ->schema([
                            TextInput::make('company_name')
                                ->label('Company name')
                                ->required()
                                ->columnSpanFull(),

                            Textarea::make('company_address')
                                ->label('Company address')
                                ->rows(3)
                                ->columnSpanFull(),

                            TextInput::make('invoice_pdf_filename_format')
                                ->label('PDF filename format')
                                ->helperText('Placeholders: {{ number }}, {{ client }}')
                                ->required()
                                ->columnSpanFull(),
                        ]),
                ])
                    ->persistStepInQueryString('getting-started-step')
                    ->startOnStep($tenantId ? $this->resolveStartStep($tenantId) : 1)
                    ->skippable()
                    ->submitAction(new HtmlString(Blade::render(<<<'BLADE'
                        <x-filament::button type="submit" size="sm">
                            Finish setup
                        </x-filament::button>
                    BLADE))),
            ]);
    }

    public function submit(): void
    {
        $tenantId = Filament::getTenant()?->id;

        if (! $tenantId) {
            return;
        }

        $data = $this->form->getState();

        CompanySetting::set('company_name', $data['company_name'] ?? '');
        CompanySetting::set('company_address', $data['company_address'] ?? '');
        CompanySetting::set('invoice_pdf_filename_format', $data['invoice_pdf_filename_format'] ?? '');

        if (! self::isCompleted($tenantId)) {
            Notification::make()
                ->warning()
                ->title('Setup incomplete')
                ->body('Please complete all steps before continuing.')
                ->send();

            //throw new Halt();
        }

        Notification::make()
            ->success()
            ->title('Setup complete')
            ->body('You can now start creating invoices.')
            ->send();
    }

    private function resolveStartStep(int $tenantId): int
    {
        if (! $this->hasCurrencies($tenantId)) {
            return 1;
        }

        if (! $this->hasInvoiceNumberingConfigured($tenantId)) {
            return 2;
        }

        if (! $this->hasInvoiceDetailsConfigured($tenantId)) {
            return 3;
        }

        return 1;
    }

    private static function isCompleted(int $tenantId): bool
    {
        $self = new self();

        return $self->hasCurrencies($tenantId)
            && $self->hasInvoiceNumberingConfigured($tenantId)
            && $self->hasInvoiceDetailsConfigured($tenantId);
    }

    private function hasCurrencies(int $tenantId): bool
    {
        return Currency::where('company_id', $tenantId)->exists();
    }

    private function hasInvoiceNumberingConfigured(int $tenantId): bool
    {
        $keys = [
            'invoice_numbering_reset_yearly',
            'invoice_numbering_pad_zeros',
            'invoice_numbering_starting_number',
            'invoice_numbering_prefix',
        ];

        return CompanySetting::where('company_id', $tenantId)
            ->whereIn('key', $keys)
            ->exists();
    }

    private function hasInvoiceDetailsConfigured(int $tenantId): bool
    {
        $name = (string) CompanySetting::get('company_name');
        $pdfFormat = (string) CompanySetting::get('invoice_pdf_filename_format');

        return $name !== '' && $pdfFormat !== '';
    }
}
