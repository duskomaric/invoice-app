<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Filament\Clusters\Settings\Pages\Appearance as AppearanceSettingsPage;
use App\Filament\Clusters\Settings\Pages\Email as EmailSettingsPage;
use App\Filament\Clusters\Settings\Pages\Fiscalization as FiscalizationSettingsPage;
use App\Filament\Clusters\Settings\Pages\Invoice as InvoiceSettingsPage;
use App\Filament\Clusters\Settings\Resources\CompanyBankAccounts\CompanyBankAccountResource;
use App\Filament\Clusters\Settings\Resources\Currencies\CurrencyResource;
use App\Filament\Clusters\Settings\Resources\EmailSignature\EmailSignatureResource;
use App\Filament\Clusters\Settings\Resources\EmailTemplateResource\EmailTemplateResource;
use App\Models\CompanySetting;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class GettingStartedWidget extends Widget implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.widgets.getting-started-widget';

    protected int|string|array $columnSpan = 'full';

    public ?array $data = [];

    public function mount(): void
    {
        $tenantId = Filament::getTenant()?->id;

        if (! $tenantId) {
            return;
        }

        $checklist = CompanySetting::get('getting_started_checklist', [], $tenantId);
        if (! is_array($checklist)) {
            $checklist = [];
        }

        $this->form->fill([
            'checklist' => $checklist,
        ]);
    }

    private function wizardSteps(?int $tenantId): array
    {
        $steps = $this->steps();

        return array_map(function (array $step, int $index) use ($tenantId) {
            return Step::make($step['title'])
                ->icon($step['icon'])
                ->completedIcon(Heroicon::OutlinedCheckCircle)
                ->schema([
                    \Filament\Schemas\Components\Section::make()
                        ->icon(Heroicon::OutlinedExclamationTriangle)
                        ->iconColor('warning')
                        ->schema([
                            Placeholder::make($step['key'].'_info')
                                ->label('')
                                ->content($step['description'])
                                ->hint('This step is required before issuing invoices')
                                ->hintIcon(Heroicon::OutlinedInformationCircle)
                                ->hintColor('warning'),

                            Action::make('open')
                                ->label('Open page')
                                ->url($step['url'])
                                ->color('gray'),
                        ]),
                ])
                ->afterValidation(function () use ($tenantId, $step) {
                    if (! $tenantId) {
                        return;
                    }

                    $checklist = CompanySetting::get('getting_started_checklist', [], $tenantId);
                    if (! is_array($checklist)) {
                        $checklist = [];
                    }

                    $checklist[$step['key']] = true;

                    CompanySetting::set('getting_started_checklist', $checklist, $tenantId);

                    $data = $this->form->getState();
                    $this->form->fill([
                        ...$data,
                        'checklist' => $checklist,
                    ]);
                });
        }, $steps, array_keys($steps));
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
                Wizard::make($this->wizardSteps($tenantId))
                    ->startOnStep($tenantId ? $this->resolveStartStep($tenantId) : 1)
                    ->skippable(false)
                    ->submitAction(new HtmlString(Blade::render(<<<'BLADE'
                        <x-filament::button type="submit" size="sm">
                            Save setup
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

        $checklist = CompanySetting::get('getting_started_checklist', [], $tenantId);
        if (! is_array($checklist)) {
            $checklist = [];
        }

        $steps = $this->steps();
        $lastStep = $steps !== [] ? $steps[array_key_last($steps)] : null;
        if (is_array($lastStep) && isset($lastStep['key'])) {
            $checklist[(string) $lastStep['key']] = true;
        }

        CompanySetting::set('getting_started_checklist', $checklist, $tenantId);

        Notification::make()
            ->success()
            ->title('Setup saved')
            ->body('You can now start creating invoices.')
            ->send();
    }

    private function resolveStartStep(int $tenantId): int
    {
        $checklist = CompanySetting::get('getting_started_checklist', [], $tenantId);
        if (! is_array($checklist)) {
            $checklist = [];
        }

        $steps = $this->steps();
        foreach ($steps as $index => $step) {
            $key = $step['key'];
            if (! (bool) ($checklist[$key] ?? false)) {
                return $index + 1;
            }
        }

        return 1;
    }

    private static function isCompleted(int $tenantId): bool
    {
        $checklist = CompanySetting::get('getting_started_checklist', [], $tenantId);
        if (! is_array($checklist)) {
            $checklist = [];
        }

        $requiredKeys = [
            'company_bank_accounts',
            'currencies',
            'email_signatures',
            'email_templates',
            'appearance',
            'invoice',
            'fiscalization',
            'email',
        ];

        foreach ($requiredKeys as $key) {
            if (! (bool) ($checklist[$key] ?? false)) {
                return false;
            }
        }

        return true;
    }

    private function steps(): array
    {
        return [
            [
                'key' => 'company_bank_accounts',
                'title' => 'Company Bank Accounts',
                'description' => 'Add at least one company bank account. It is used on invoice PDFs, and it allows you to choose defaults so invoices are generated with correct payment details.',
                'url' => CompanyBankAccountResource::getUrl('index'),
                'icon' => Heroicon::OutlinedBuildingLibrary,
            ],
            [
                'key' => 'currencies',
                'title' => 'Currencies',
                'description' => 'Create the currencies you will issue invoices in. Invoices need a currency for totals, reporting, and templates. If you use currency-based numbering, currency code is also used as the invoice prefix.',
                'url' => CurrencyResource::getUrl('index'),
                'icon' => Heroicon::OutlinedDocumentCurrencyEuro,
            ],
            [
                'key' => 'email_signatures',
                'title' => 'Email Signatures',
                'description' => 'Create and select an email signature used when sending invoices and other documents. It helps ensure consistent branding and reduces manual typing for each email.',
                'url' => EmailSignatureResource::getUrl('index'),
                'icon' => Heroicon::OutlinedPencilSquare,
            ],
            [
                'key' => 'email_templates',
                'title' => 'Email Templates',
                'description' => 'Configure default email subject/body templates. This ensures invoices are sent with a consistent message and correct placeholders without having to rewrite emails each time.',
                'url' => EmailTemplateResource::getUrl('index'),
                'icon' => Heroicon::OutlinedDocumentText,
            ],
            [
                'key' => 'appearance',
                'title' => 'Appearance',
                'description' => 'Set your company dashboard appearance (theme colors, pagination, layout). This helps match your branding and improves the user experience for your team.',
                'url' => AppearanceSettingsPage::getUrl(),
                'icon' => Heroicon::OutlinedAdjustmentsHorizontal,
            ],
            [
                'key' => 'invoice',
                'title' => 'Invoice',
                'description' => 'Configure invoice defaults: language, template, due days, bank account defaults, numbering rules, and PDF filename formatting. This is the main setup required before creating invoices.',
                'url' => InvoiceSettingsPage::getUrl(),
                'icon' => Heroicon::OutlinedDocumentText,
            ],
            [
                'key' => 'fiscalization',
                'title' => 'Fiscalization',
                'description' => 'If you use fiscalization, enter your OFS credentials and seller details. This is required to fiscalize invoices and generate legally compliant fiscal data.',
                'url' => FiscalizationSettingsPage::getUrl(),
                'icon' => Heroicon::OutlinedReceiptPercent,
            ],
            [
                'key' => 'email',
                'title' => 'Email',
                'description' => 'Configure SMTP so the system can send invoices from your company domain. Without this, invoice sending may fail or use default system email settings.',
                'url' => EmailSettingsPage::getUrl(),
                'icon' => Heroicon::OutlinedEnvelope,
            ],
        ];
    }
}
