<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings\SettingsCluster;
use App\Models\Setting;
use BackedEnum;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\HtmlString;

class InvoiceNumbering extends Page
{
    use InteractsWithForms;

    protected static ?string $cluster = SettingsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHashtag;

    protected static ?string $navigationLabel = 'Invoice Numbering';

    protected static ?int $navigationSort = 4;

    protected string $view = 'filament.pages.settings';

    public function mount(): void
    {
        $this->form->fill([
            'invoice_prefixes' => Setting::get('invoice_prefixes', ['BAM' => 'BAM', 'EUR' => 'EUR']),
            'invoice_default_currency' => (string) Setting::get('invoice_default_currency', 'BAM'),
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('Currency Prefixes')
                ->description('Configure invoice number prefixes for each currency. Format: PREFIX-###/YEAR')
                ->schema([
                    KeyValue::make('invoice_prefixes')
                        ->label('Currency Prefixes')
                        ->keyLabel('Currency Code (e.g., EUR, BAM)')
                        ->valueLabel('Prefix')
                        ->helperText('Example: EUR → EUR will create invoices like EUR-001/'.date('Y'))
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
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Setting::set('invoice_prefixes', $data['invoice_prefixes'] ?? ['BAM' => 'BAM', 'EUR' => 'EUR']);
        Setting::set('invoice_default_currency', $data['invoice_default_currency'] ?? 'BAM');

        Notification::make()
            ->success()
            ->title('Settings saved successfully.')
            ->send();
    }
}
