<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings\SettingsCluster;
use App\Models\Setting;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;

class Email extends Page
{
    use InteractsWithForms;

    protected static ?string $cluster = SettingsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static ?string $navigationLabel = 'Email';

    protected static ?int $navigationSort = 5;

    protected string $view = 'filament.pages.settings';

    public string $smtp_host;
    public string $smtp_port;
    public string $smtp_username;
    public string $smtp_password;
    public string $smtp_encryption;
    public string $smtp_from_address;
    public string $smtp_from_name;

    public function mount(): void
    {
        $this->form->fill([
            'smtp_host' => (string) Setting::get('smtp_host'),
            'smtp_port' => (string) Setting::get('smtp_port'),
            'smtp_username' => (string) Setting::get('smtp_username'),
            'smtp_password' => (string) Setting::get('smtp_password'),
            'smtp_encryption' => (string) Setting::get('smtp_encryption'),
            'smtp_from_address' => (string) Setting::get('smtp_from_address'),
            'smtp_from_name' => (string) Setting::get('smtp_from_name'),
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('SMTP Configuration')
                ->description('Configure your email server settings. Leave blank to use the system default.')
                ->schema([
                    TextInput::make('smtp_host')
                        ->label('SMTP Host')
                        ->placeholder('smtp.mailtrap.io'),
                    TextInput::make('smtp_port')
                        ->label('SMTP Port')
                        ->numeric()
                        ->placeholder('587'),
                    TextInput::make('smtp_username')
                        ->label('SMTP Username'),
                    TextInput::make('smtp_password')
                        ->label('SMTP Password')
                        ->password()
                        ->revealable(),
                    TextInput::make('smtp_encryption')
                        ->label('Encryption')
                        ->placeholder('tls')
                        ->helperText('Usually "tls" or "ssl"'),
                    TextInput::make('smtp_from_address')
                        ->label('From Email Address')
                        ->email()
                        ->placeholder('noreply@yourdomain.com'),
                    TextInput::make('smtp_from_name')
                        ->label('From Name')
                        ->placeholder('Your Company Name'),
                ])
                ->columns(2),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Setting::set('smtp_host', $data['smtp_host'] ?? '');
        Setting::set('smtp_port', $data['smtp_port'] ?? '');
        Setting::set('smtp_username', $data['smtp_username'] ?? '');
        Setting::set('smtp_password', $data['smtp_password'] ?? '');
        Setting::set('smtp_encryption', $data['smtp_encryption'] ?? '');
        Setting::set('smtp_from_address', $data['smtp_from_address'] ?? '');
        Setting::set('smtp_from_name', $data['smtp_from_name'] ?? '');

        Notification::make()
            ->success()
            ->title('Settings saved successfully.')
            ->send();
    }
}
