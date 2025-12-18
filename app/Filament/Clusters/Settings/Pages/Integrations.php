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
use Illuminate\Support\HtmlString;

class Integrations extends Page
{
    use InteractsWithForms;

    protected static ?string $cluster = SettingsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLink;

    protected static ?string $navigationLabel = 'Integrations';

    protected static string | \UnitEnum | null $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 8;

    protected string $view = 'filament.pages.settings';

    public string $logViewerUrl = '#';

    public string $github_token = '';
    public string $log_viewer_access_key = '';
    public string $support_link = '';

    public function mount(): void
    {
        $this->form->fill([
            'github_token' => Setting::get('github_token'),
            'log_viewer_access_key' => Setting::get('log_viewer_access_key'),
            'support_link' => Setting::get('support_link'),
        ]);

        $this->logViewerUrl = Setting::get('log_viewer_access_key')
            ? url('/log-viewer?key='.Setting::get('log_viewer_access_key'))
            : '#';
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('External Services')
                ->description('Configure tokens, access keys, and support links for integrations.')
                ->schema([
                    TextInput::make('github_token')
                        ->label('GitHub Token')
                        ->helperText(new HtmlString('Generate a new token on <a href="https://github.com/settings/tokens" target="_blank" class="fi-link">GitHub</a>'))
                        ->columnSpanFull(),

                    TextInput::make('log_viewer_access_key')
                        ->label('Log Viewer Access Key')
                        ->helperText(new HtmlString('Set an access key to secure the <a href="'.$this->logViewerUrl.'" target="_blank" class="fi-link">Log Viewer</a>.'))
                        ->columnSpanFull(),

                    TextInput::make('support_link')
                        ->label('Support Link')
                        ->helperText('URL to your support portal or helpdesk.')
                        ->columnSpanFull(),
                ])->columns(12),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Setting::set('github_token', $data['github_token'] ?? '');
        Setting::set('log_viewer_access_key', $data['log_viewer_access_key'] ?? '');
        Setting::set('support_link', $data['support_link'] ?? '');

        $this->logViewerUrl = Setting::get('log_viewer_access_key')
            ? url('/log-viewer?key='.Setting::get('log_viewer_access_key'))
            : '#';

        Notification::make()
            ->success()
            ->title('Settings saved successfully.')
            ->send();
    }
}
