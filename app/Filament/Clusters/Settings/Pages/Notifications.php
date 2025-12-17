<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings\SettingsCluster;
use App\Models\Setting;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;

class Notifications extends Page
{
    use InteractsWithForms;

    protected static ?string $cluster = SettingsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBell;

    protected static ?string $navigationLabel = 'Notifications';

    protected static ?int $navigationSort = 6;

    protected string $view = 'filament.pages.settings';

    public function mount(): void
    {
        $this->form->fill([
            'notification_text' => Setting::get('notification_text'),
            'notification_type' => Setting::get('notification_type'),
            'notification_enabled' => Setting::get('notification_enabled'),
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('Alert Settings')
                ->description('Manage system notifications and alerts.')
                ->schema([
                    Textarea::make('notification_text')
                        ->label('Notification Text')
                        ->rows(5)
                        ->columnSpanFull(),

                    Select::make('notification_type')
                        ->label('Notification Type')
                        ->options([
                            'info' => 'Info',
                            'success' => 'Success',
                            'warning' => 'Warning',
                            'danger' => 'Danger',
                        ])
                        ->columnSpan(4),

                    Toggle::make('notification_enabled')
                        ->label('Enable Notification')
                        ->inline(false)
                        ->columnSpan(3),
                ])->columns(12),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Setting::set('notification_text', $data['notification_text'] ?? '');
        Setting::set('notification_type', $data['notification_type'] ?? 'info');
        Setting::set('notification_enabled', $data['notification_enabled'] ?? false);

        Notification::make()
            ->success()
            ->title('Settings saved successfully.')
            ->send();
    }
}
