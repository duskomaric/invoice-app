<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings\SettingsCluster;
use App\Models\CompanySetting;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;

class Fiscalization extends Page
{
    use InteractsWithForms;

    protected static ?string $cluster = SettingsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedReceiptPercent;

    protected static ?string $navigationLabel = 'Fiscalization';

    protected static string|\UnitEnum|null $navigationGroup = 'Company Settings';

    protected static ?int $navigationSort = 3;

    protected string $view = 'filament.pages.settings';

    public function mount(): void
    {
        $this->form->fill([
            'ofs_base_url' => (string) CompanySetting::get('ofs_base_url'),
            'ofs_api_key' => (string) CompanySetting::get('ofs_api_key'),
            'ofs_serial_number' => (string) CompanySetting::get('ofs_serial_number'),
            'ofs_pac' => (string) CompanySetting::get('ofs_pac'),
            'ofs_seller_tin' => (string) CompanySetting::get('ofs_seller_tin'),
            'ofs_seller_name' => (string) CompanySetting::get('ofs_seller_name'),
            'ofs_seller_address' => (string) CompanySetting::get('ofs_seller_address'),
            'ofs_seller_town' => (string) CompanySetting::get('ofs_seller_town'),
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('OFS ESIR API Configuration')
                ->description('Configure OFS (Operator Fiskalnog Sistema) API credentials for electronic fiscalization.')
                ->schema([
                    TextInput::make('ofs_base_url')
                        ->label('API Base URL')
                        ->placeholder('https://pos.ofs.ba')
                        ->url()
                        ->helperText('Base URL for OFS API endpoint')
                        ->required()
                        ->columnSpan(6),

                    TextInput::make('ofs_api_key')
                        ->label('API Key')
                        ->password()
                        ->revealable()
                        ->helperText('Your OFS API key / Bearer token')
                        ->required()
                        ->columnSpan(6),

                    TextInput::make('ofs_serial_number')
                        ->label('Serial Number')
                        ->helperText('X-Teron-SerialNumber header value')
                        ->required()
                        ->columnSpan(6),

                    TextInput::make('ofs_pac')
                        ->label('PAC (Pristupni Kod)')
                        ->helperText('X-PAC header value')
                        ->required()
                        ->columnSpan(6),
                ])->columns(12),

            Section::make('Seller Information')
                ->description('Your company details that will appear on fiscalized invoices. This information is sent to the OFS API.')
                ->schema([
                    TextInput::make('ofs_seller_tin')
                        ->label('JIB/PIB (Tax ID)')
                        ->helperText('Vaš 12-cifreni JIB ili PIB broj')
                        ->placeholder('4401136590007')
                        ->required()
                        ->columnSpan(6),

                    TextInput::make('ofs_seller_name')
                        ->label('Company Name')
                        ->helperText('Naziv vaše firme kako je registrovan')
                        ->required()
                        ->columnSpan(6),

                    TextInput::make('ofs_seller_address')
                        ->label('Address')
                        ->helperText('Adresa sjedišta firme')
                        ->required()
                        ->columnSpan(6),

                    TextInput::make('ofs_seller_town')
                        ->label('City/Town')
                        ->helperText('Grad u kojem je firma registrovana')
                        ->placeholder('Banja Luka')
                        ->required()
                        ->columnSpan(6),
                ])->columns(12),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        CompanySetting::set('ofs_base_url', $data['ofs_base_url'] ?? 'https://pos.ofs.ba');
        CompanySetting::set('ofs_api_key', $data['ofs_api_key'] ?? '');
        CompanySetting::set('ofs_serial_number', $data['ofs_serial_number'] ?? '');
        CompanySetting::set('ofs_pac', $data['ofs_pac'] ?? '');
        CompanySetting::set('ofs_seller_tin', $data['ofs_seller_tin'] ?? '');
        CompanySetting::set('ofs_seller_name', $data['ofs_seller_name'] ?? '');
        CompanySetting::set('ofs_seller_address', $data['ofs_seller_address'] ?? '');
        CompanySetting::set('ofs_seller_town', $data['ofs_seller_town'] ?? '');

        Notification::make()
            ->success()
            ->title('Settings saved successfully.')
            ->send();
    }
}
