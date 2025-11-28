<?php

namespace App\Filament\Pages;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Models\Permission;
use App\Models\PermissionRoleEnum;
use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\HtmlString;

class Settings extends Page
{
    use InteractsWithForms;

    protected static string|\UnitEnum|null $navigationGroup = 'Application Settings';

    protected static ?string $navigationLabel = 'Settings';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.settings';

    public string $notification_text;

    public string $notification_type;

    public bool $notification_enabled;

    public bool $dashboard_under_maintenance;

    public string $dashboard_under_maintenance_title;

    public mixed $dashboard_under_maintenance_text;

    public string $github_token;

    public string $log_viewer_access_key;

    public array $pagination;

    public string $support_link;

    public string $modal_width;

    public string $default_pagination_option;

    public bool $top_navigation;

    public string $primary_color;

    public string $danger_color;

    public string $gray_color;

    public string $info_color;

    public string $success_color;

    public string $warning_color;

    public ?string $selectedRole;

    public array $permissions = [];

    public string $logViewerUrl = '#';

    public function mount(): void
    {
        $this->selectedRole = RoleEnum::SuperAdmin->value;
        $this->loadPermissionsForRole($this->selectedRole);

        $this->form->fill([
            'notification_text' => Setting::get('notification_text'),
            'notification_type' => Setting::get('notification_type'),
            'notification_enabled' => Setting::get('notification_enabled'),
            'dashboard_under_maintenance' => Setting::get('dashboard_under_maintenance'),
            'dashboard_under_maintenance_title' => Setting::get('dashboard_under_maintenance_title'),
            'dashboard_under_maintenance_text' => Setting::get('dashboard_under_maintenance_text'),
            'github_token' => Setting::get('github_token'),
            'log_viewer_access_key' => Setting::get('log_viewer_access_key'),
            'pagination' => Setting::get('pagination'),
            'support_link' => Setting::get('support_link'),

            'modal_width' => Setting::get('modal_width'),
            'default_pagination_option' => Setting::get('default_pagination_option'),
            'top_navigation' => Setting::get('top_navigation'),
            'primary_color' => Setting::get('primary_color'),
            'danger_color' => Setting::get('danger_color'),
            'gray_color' => Setting::get('gray_color'),
            'info_color' => Setting::get('info_color'),
            'success_color' => Setting::get('success_color'),
            'warning_color' => Setting::get('warning_color'),
        ]);

        $this->logViewerUrl = Setting::get('log_viewer_access_key')
            ? url('/log-viewer?key='.Setting::get('log_viewer_access_key'))
            : '#';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('delete')
                ->label('Reset All Settings')
                ->color('danger')
                ->icon('heroicon-o-trash')
                ->requiresConfirmation()
                ->modalHeading('Reset All Settings')
                ->modalDescription('Are you sure you want to delete all settings? This action cannot be undone.')
                ->modalSubmitActionLabel('Yes, reset all settings')
                ->action(fn () => $this->resetSettings()),
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            Tabs::make()->tabs([

                // -----------------------
                // General Settings
                // -----------------------
                Tab::make('General')->schema([
                    Section::make('Pagination')
                        ->description('Configure pagination options for listings.')
                        ->schema([
                            TagsInput::make('pagination')
                                ->label('Pagination Options')
                                ->dehydrateStateUsing(fn ($state) => $state ?? [])
                                ->helperText('Enter the number of items per page for listings, separated by commas.')
                                ->placeholder('e.g., 10,25,50,100')
                                ->separator(',')
                                ->columnSpan(9),

                            Select::make('default_pagination_option')
                                ->label('Default Pagination Option')
                                ->options(fn () => collect(Setting::get('pagination', []))
                                    ->mapWithKeys(fn ($value) => [$value => $value])
                                    ->toArray())
                                ->columnSpan(3),
                        ])->columns(12),

                    Section::make('Modal & Navigation')
                        ->description('Configure modal dialogs and navigation layout.')
                        ->schema([
                            Select::make('modal_width')
                                ->label('Modal Width')
                                ->options(collect(Width::cases())->pluck('value', 'value')->toArray())
                                ->columnSpan(3),

                            Toggle::make('top_navigation')
                                ->label('Use Top Navigation')
                                ->inline(false)
                                ->default(Setting::get('top_navigation'))
                                ->columnSpan(3),
                        ])->columns(12),

                    Section::make('Theme Colors')
                        ->description('Customize the application color palette.')
                        ->schema(
                            collect([
                                'primary_color' => 'Primary Color',
                                'danger_color' => 'Danger Color',
                                'gray_color' => 'Gray Color',
                                'info_color' => 'Info Color',
                                'success_color' => 'Success Color',
                                'warning_color' => 'Warning Color',
                            ])->map(fn ($label, $key) => Select::make($key)
                                ->label($label)
                                ->options(fn () => array_combine(
                                    array_map('ucfirst', array_keys(Color::all())),
                                    array_map('ucfirst', array_keys(Color::all()))
                                ))
                                ->default(Setting::get($key))
                                ->columnSpan(2)
                            )->toArray()
                        )->columns(6),

                ]),

                // -----------------------
                // Notifications
                // -----------------------
                Tab::make('Notifications')->schema([
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
                ]),

                // -----------------------
                // Maintenance Mode
                // -----------------------
                Tab::make('Maintenance Mode')->schema([

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
                ]),

                // -----------------------
                // Integrations
                // -----------------------
                Tab::make('Integrations')->schema([
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
                ]),

                // -----------------------
                // Role Permissions
                // -----------------------
                Tab::make('Role Permissions')->schema([
                    Section::make('Permissions')
                        ->description('Assign and manage permissions for each role.')
                        ->schema($this->buildPermissionsFields()),
                ]),
            ]),
        ];
    }

    protected function buildPermissionsFields(): array
    {
        $fields = [];

        // Role selector
        $fields[] = Select::make('selectedRole')
            ->label('Role')
            ->options(array_combine(
                array_map(fn ($r) => $r->value, RoleEnum::cases()),
                array_map(fn ($r) => $r->getLabel(), RoleEnum::cases())
            ))
            ->reactive()
            ->afterStateUpdated(fn ($state) => $this->loadPermissionsForRole($state))
            ->required()
            ->helperText('Select a role to auto-load its permissions.');

        // General info
        $fields[] = Placeholder::make('permissions_text')
            ->label('Permissions Info')
            ->helperText(
                'Permissions control the user\'s access to different sections and features of the application.'
            );

        // Group permissions
        $groupedPermissions = collect(PermissionEnum::cases())
            ->groupBy(fn ($enum) => $enum->group());

        foreach ($groupedPermissions as $groupName => $permissions) {
            $fieldset = Fieldset::make($groupName)
                ->schema(
                    collect($permissions)->map(function ($permission) {
                        return Checkbox::make("permissions.{$permission->value}")
                            ->label($permission->publicName())
                            ->helperText($permission->description())
                            ->columnSpan(3);

                    })->toArray()
                )
                ->columns(12);

            $fields[] = $fieldset;
        }

        return $fields;
    }

    public function loadPermissionsForRole(string $role): void
    {
        $this->selectedRole = $role;

        $permissionIds = PermissionRoleEnum::where('role', $role)
            ->pluck('permission_id')
            ->toArray();

        $this->permissions = Permission::all()
            ->mapWithKeys(fn ($perm) => [
                $perm->name => in_array($perm->id, $permissionIds),
            ])
            ->toArray();
    }

    public function save(): void
    {

        $data = $this->form->getState();

        Setting::set('pagination', $data['pagination'] ?? [10, 25, 50, 100]);
        Setting::set('notification_text', $data['notification_text'] ?? '');
        Setting::set('notification_type', $data['notification_type'] ?? 'info');
        Setting::set('notification_enabled', $data['notification_enabled'] ?? false);
        Setting::set('dashboard_under_maintenance', $data['dashboard_under_maintenance'] ?? false);
        Setting::set('dashboard_under_maintenance_title', $data['dashboard_under_maintenance_title'] ?? '');
        Setting::set('dashboard_under_maintenance_text', $data['dashboard_under_maintenance_text'] ?? '');
        Setting::set('github_token', $data['github_token'] ?? '');
        Setting::set('log_viewer_access_key', $data['log_viewer_access_key'] ?? '');
        Setting::set('support_link', $data['support_link'] ?? '');

        Setting::set('modal_width', $data['modal_width'] ?? Width::Medium->value);
        Setting::set('default_pagination_option', $data['default_pagination_option'] ?? 10);
        Setting::set('top_navigation', $data['top_navigation'] ?? false);
        Setting::set('primary_color', $data['primary_color'] ?? Color::Blue->value);
        Setting::set('danger_color', $data['danger_color'] ?? Color::Red->value);
        Setting::set('gray_color', $data['gray_color'] ?? Color::Gray->value);
        Setting::set('info_color', $data['info_color'] ?? Color::Blue->value);
        Setting::set('success_color', $data['success_color'] ?? Color::Green->value);
        Setting::set('warning_color', $data['warning_color'] ?? Color::Yellow->value);

        // Your existing permissions logic...
        //        if (!empty($this->selectedRole)) {
        //            $role = $this->selectedRole;
        //
        //            PermissionRoleEnum::where('role', $role)->delete();
        //
        //            $selected = array_keys(array_filter($this->permissions));
        //
        //            $rows = collect($selected)->map(fn ($permissionName) => [
        //                'role' => $role,
        //                'permission_id' => Permission::where('name', $permissionName)->first()->id,
        //                'created_at' => now(),
        //                'updated_at' => now(),
        //            ])->toArray();
        //
        //            PermissionRoleEnum::insert($rows);
        //        }

        Notification::make()
            ->success()
            ->title('Settings saved successfully.')
            ->send();
    }

    public function resetSettings(): void
    {
        Setting::truncate();
        Setting::flushCache();

        $this->mount(); // reload defaults

        Notification::make()
            ->success()
            ->title('All settings have been reset.')
            ->send();
    }
}
