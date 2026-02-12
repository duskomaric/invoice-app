<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Enums\RoleEnum;
use App\Filament\Clusters\Settings\SettingsCluster;
use App\Models\Setting;
use BackedEnum;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;

class Maintenance extends Page
{
    use InteractsWithForms;

    protected static ?string $cluster = SettingsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    protected static ?string $navigationLabel = 'Maintenance';

    protected static string | \UnitEnum | null $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 7;

    protected string $view = 'filament.pages.settings';

    public bool $dashboard_under_maintenance;
    public string $dashboard_under_maintenance_title;
    public mixed $dashboard_under_maintenance_text;

    public function mount(): void
    {
        $this->form->fill([
            'dashboard_under_maintenance' => Setting::get('dashboard_under_maintenance'),
            'dashboard_under_maintenance_title' => Setting::get('dashboard_under_maintenance_title'),
            'dashboard_under_maintenance_text' => Setting::get('dashboard_under_maintenance_text'),
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('Dashboard Maintenance')
                ->description('Configure maintenance mode settings for the dashboard.')
                ->schema([
                    Toggle::make('dashboard_under_maintenance')
                        ->label('Enable Maintenance Mode')
                        ->helperText('Users will see a maintenance page. Only '.RoleEnum::SuperAdmin->getLabel().' users can access the dashboard when enabled.')
                        ->columnSpanFull(),

                    TextInput::make('dashboard_under_maintenance_title')
                        ->label('Maintenance Page Title')
                        ->placeholder('Dashboard Under Maintenance')
                        ->columnSpanFull(),

                    RichEditor::make('dashboard_under_maintenance_text')
                        ->label('Maintenance Page Text')
                        ->placeholder('The dashboard is currently under maintenance. Please check back later.')
                        ->grow()
                        ->floatingToolbars([
                            'paragraph' => ['bold', 'italic', 'underline', 'strike'],
                        ])
                        ->toolbarButtons([
                            ['bold', 'italic', 'underline', 'strike'],
                            ['alignStart', 'alignCenter', 'alignEnd'],
                            ['attachFiles'],
                            ['undo', 'redo'],
                        ])
                        ->fileAttachmentsDisk('public')
                        ->fileAttachmentsDirectory('rich-editor')
                        ->fileAttachmentsVisibility('public')
                        ->columnSpanFull(),
                ])->columns(12),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Setting::set('dashboard_under_maintenance', $data['dashboard_under_maintenance'] ?? false);
        Setting::set('dashboard_under_maintenance_title', $data['dashboard_under_maintenance_title'] ?? '');
        Setting::set('dashboard_under_maintenance_text', $data['dashboard_under_maintenance_text'] ?? '');

        Notification::make()
            ->success()
            ->title('Settings saved successfully.')
            ->send();
    }
}
