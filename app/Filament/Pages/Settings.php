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

    public $invoice_email_subject;

    public $invoice_email_body;

    public $invoice_email_subject_sr;
    public $invoice_email_body_sr;

    public $invoice_pdf_filename_format;

    public $company_name;
    public $company_address;
    public $company_email;
    public $company_phone;
    public $company_vat_id;
    public $company_bank_account;

    // OFS Fiscalization Settings
    public $ofs_base_url;
    public $ofs_api_key;
    public $ofs_serial_number;
    public $ofs_pac;
    public $ofs_seller_tin;
    public $ofs_seller_name;
    public $ofs_seller_address;
    public $ofs_seller_town;

    // Invoice Numbering Settings
    public $invoice_prefixes;
    public $invoice_default_currency;

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

            'invoice_email_subject' => (string) Setting::get('invoice_email_subject'),
            'invoice_email_body' => (string) Setting::get('invoice_email_body'),
            'invoice_email_subject_sr' => (string) Setting::get('invoice_email_subject_sr'),
            'invoice_email_body_sr' => (string) Setting::get('invoice_email_body_sr'),
            'invoice_pdf_filename_format' => (string) Setting::get('invoice_pdf_filename_format'),

            'company_name' => (string) Setting::get('company_name'),
            'company_address' => (string) Setting::get('company_address'),
            'company_email' => (string) Setting::get('company_email'),
            'company_phone' => (string) Setting::get('company_phone'),
            'company_vat_id' => (string) Setting::get('company_vat_id'),
            'company_bank_account' => (string) Setting::get('company_bank_account'),

            'ofs_base_url' => (string) Setting::get('ofs_base_url'),
            'ofs_api_key' => (string) Setting::get('ofs_api_key'),
            'ofs_serial_number' => (string) Setting::get('ofs_serial_number'),
            'ofs_pac' => (string) Setting::get('ofs_pac'),
            'ofs_seller_tin' => (string) Setting::get('ofs_seller_tin'),
            'ofs_seller_name' => (string) Setting::get('ofs_seller_name'),
            'ofs_seller_address' => (string) Setting::get('ofs_seller_address'),
            'ofs_seller_town' => (string) Setting::get('ofs_seller_town'),

            'invoice_prefixes' => Setting::get('invoice_prefixes', ['BAM' => 'BAM', 'EUR' => 'EUR']),
            'invoice_default_currency' => (string) Setting::get('invoice_default_currency', 'BAM'),
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
                // Invoice Settings
                // -----------------------
                Tab::make('Invoice Settings')->schema([
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

                    Section::make('Email Configuration')
                        ->description('Configure the email template sent to clients.')
                        ->schema([
                            Fieldset::make('English Template')
                                ->schema([
                                    TextInput::make('invoice_email_subject')
                                        ->label('Email Subject')
                                        ->helperText('Available placeholders: {{ number }}')
                                        ->required()
                                        ->columnSpanFull(),

                                    RichEditor::make('invoice_email_body')
                                        ->label('Email Body (Markdown)')
                                        ->helperText('Available placeholders: {{ client }}, {{ number }}, {{ amount }}, {{ due_date }}, {{ company }}')
                                        ->toolbarButtons([
                                            ['bold', 'italic', 'underline', 'strike'],
                                            ['bulletList', 'orderedList'],
                                            ['link'],
                                            ['undo', 'redo'],
                                        ])
                                        ->required()
                                        ->columnSpanFull(),
                                ])->columnSpanFull()->columns(12),

                            Fieldset::make('Serbian (Latin) Template')
                                ->schema([
                                    TextInput::make('invoice_email_subject_sr')
                                        ->label('Email Subject')
                                        ->helperText('Available placeholders: {{ number }}')
                                        ->required()
                                        ->columnSpanFull(),

                                    RichEditor::make('invoice_email_body_sr')
                                        ->label('Email Body (Markdown)')
                                        ->helperText('Available placeholders: {{ client }}, {{ number }}, {{ amount }}, {{ due_date }}, {{ company }}')
                                        ->toolbarButtons([
                                            ['bold', 'italic', 'underline', 'strike'],
                                            ['bulletList', 'orderedList'],
                                            ['link'],
                                            ['undo', 'redo'],
                                        ])
                                        ->required()
                                        ->columnSpanFull(),
                                ])->columnSpanFull()->columns(12),
                        ])->columnSpanFull()->columns(12),

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
                ]),

                // -----------------------
                // Fiscalization Settings
                // -----------------------
                Tab::make('Fiscalization')->schema([
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
//                                ->maxLength(12)
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
                ]),

                // -----------------------
                // Invoice Numbering
                // -----------------------
                Tab::make('Invoice Numbering')->schema([
                    Section::make('Currency Prefixes')
                        ->description('Configure invoice number prefixes for each currency. Format: PREFIX-###/YEAR')
                        ->schema([
                            \Filament\Forms\Components\KeyValue::make('invoice_prefixes')
                                ->label('Currency Prefixes')
                                ->keyLabel('Currency Code (e.g., EUR, BAM)')
                                ->valueLabel('Prefix')
                                ->helperText('Example: EUR → EUR will create invoices like EUR-001/'. date('Y'))
                                ->addButtonLabel('Add Currency')
                                ->reorderable(false)
                                ->columnSpanFull(),

                            Select::make('invoice_default_currency')
                                ->label('Default Currency')
                                ->options(fn ($get) => $get('invoice_prefixes') ?? ['BAM' => 'BAM', 'EUR' => 'EUR'])
                                ->required()
                                ->default('BAM')
                                ->columnSpan(6),
                        ])->columns(12),

                    Section::make('Sequence Counters')
                        ->description('Current invoice numbers per currency and year. These update automatically.')
                        ->schema([
                            Placeholder::make('sequences_display')
                                ->label('Current Sequences')
                                ->content(function () {
                                    $sequences = Setting::get('invoice_sequences', []);
                                    if (empty($sequences)) {
                                        return new HtmlString('<p class="text-sm text-gray-500">No invoices created yet.</p>');
                                    }

                                    $output = '<div class="space-y-2">';
                                    foreach ($sequences as $currency => $years) {
                                        $output .= '<div class="font-semibold">' . $currency . ':</div>';
                                        $output .= '<ul class="ml-4 space-y-1">';
                                        foreach ($years as $year => $number) {
                                            $output .= '<li class="text-sm">Year ' . $year . ': <span class="font-mono">' . $number . '</span></li>';
                                        }
                                        $output .= '</ul>';
                                    }
                                    $output .= '</div>';

                                    return new HtmlString($output);
                                })
                                ->columnSpanFull(),
                        ]),
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

        Setting::set('invoice_email_subject', $data['invoice_email_subject'] ?? '');
        Setting::set('invoice_email_body', $data['invoice_email_body'] ?? '');
        Setting::set('invoice_email_subject_sr', $data['invoice_email_subject_sr'] ?? '');
        Setting::set('invoice_email_body_sr', $data['invoice_email_body_sr'] ?? '');
        Setting::set('invoice_pdf_filename_format', $data['invoice_pdf_filename_format'] ?? '');

        Setting::set('company_name', $data['company_name'] ?? '');
        Setting::set('company_address', $data['company_address'] ?? '');
        Setting::set('company_email', $data['company_email'] ?? '');
        Setting::set('company_phone', $data['company_phone'] ?? '');
        Setting::set('company_vat_id', $data['company_vat_id'] ?? '');
        Setting::set('company_bank_account', $data['company_bank_account'] ?? '');

        Setting::set('ofs_base_url', $data['ofs_base_url'] ?? 'https://pos.ofs.ba');
        Setting::set('ofs_api_key', $data['ofs_api_key'] ?? '');
        Setting::set('ofs_serial_number', $data['ofs_serial_number'] ?? '');
        Setting::set('ofs_pac', $data['ofs_pac'] ?? '');
        Setting::set('ofs_seller_tin', $data['ofs_seller_tin'] ?? '');
        Setting::set('ofs_seller_name', $data['ofs_seller_name'] ?? '');
        Setting::set('ofs_seller_address', $data['ofs_seller_address'] ?? '');
        Setting::set('ofs_seller_town', $data['ofs_seller_town'] ?? '');

        Setting::set('invoice_prefixes', $data['invoice_prefixes'] ?? ['BAM' => 'BAM', 'EUR' => 'EUR']);
        Setting::set('invoice_default_currency', $data['invoice_default_currency'] ?? 'BAM');

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
