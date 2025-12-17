<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings\SettingsCluster;
use App\Models\Setting;
use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;

class Invoice extends Page
{
    use InteractsWithForms;

    protected static ?string $cluster = SettingsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $navigationLabel = 'Invoice';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.settings';

    public function mount(): void
    {
        $this->form->fill([
            'invoice_pdf_filename_format' => (string) Setting::get('invoice_pdf_filename_format'),
            'company_name' => (string) Setting::get('company_name'),
            'company_address' => (string) Setting::get('company_address'),
            'company_email' => (string) Setting::get('company_email'),
            'company_phone' => (string) Setting::get('company_phone'),
            'company_vat_id' => (string) Setting::get('company_vat_id'),
            'company_bank_account' => (string) Setting::get('company_bank_account'),
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('Company Information')
                ->description('Configure your company details to be displayed on invoices.')
                ->schema([
                    TextInput::make('company_name')
                        ->label('Company Name')
                        ->required()
                        ->columnSpan(6),
                    TextInput::make('company_email')
                        ->label('Company Email')
                        ->email()
                        ->columnSpan(6),
                    Textarea::make('company_address')
                        ->label('Company Address')
                        ->rows(3)
                        ->columnSpanFull(),
                    TextInput::make('company_phone')
                        ->label('Company Phone')
                        ->tel()
                        ->columnSpan(6),
                    TextInput::make('company_vat_id')
                        ->label('Company VAT / Tax ID')
                        ->columnSpan(6),
                    TextInput::make('company_bank_account')
                        ->label('Bank Account / IBAN')
                        ->columnSpanFull(),
                ])->columns(12),

            Section::make('PDF Configuration')
                ->description('Configure the generated PDF invoice.')
                ->schema([
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

        Setting::set('invoice_pdf_filename_format', $data['invoice_pdf_filename_format'] ?? '');
        Setting::set('company_name', $data['company_name'] ?? '');
        Setting::set('company_address', $data['company_address'] ?? '');
        Setting::set('company_email', $data['company_email'] ?? '');
        Setting::set('company_phone', $data['company_phone'] ?? '');
        Setting::set('company_vat_id', $data['company_vat_id'] ?? '');
        Setting::set('company_bank_account', $data['company_bank_account'] ?? '');

        Notification::make()
            ->success()
            ->title('Settings saved successfully.')
            ->send();
    }
}
