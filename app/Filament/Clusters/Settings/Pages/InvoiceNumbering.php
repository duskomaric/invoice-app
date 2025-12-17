<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings\SettingsCluster;
use App\Models\Setting;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;

class InvoiceNumbering extends Page
{
    use InteractsWithForms;

    protected static ?string $cluster = SettingsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHashtag;

    protected static ?string $navigationLabel = 'Invoice Numbering';

    protected static ?int $navigationSort = 4;

    protected string $view = 'filament.pages.settings';

    public string $invoice_numbering_format;
    public int $invoice_numbering_pad_length;
    public int $invoice_numbering_start_number;


    public function mount(): void
    {
        $this->form->fill([
            'invoice_numbering_format' => (string) Setting::get('invoice_numbering_format', '{prefix}-{number}/{year}'),
            'invoice_numbering_pad_length' => (int) Setting::get('invoice_numbering_pad_length', 3),
            'invoice_numbering_start_number' => (int) Setting::get('invoice_numbering_start_number', 1),
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('Numbering Rules')
                ->description('Configure how invoice numbers are generated.')
                ->schema([
                    TextInput::make('invoice_numbering_format')
                        ->label('Number Format')
                        ->helperText('Placeholders: {prefix}, {currency}, {number}, {year}, {month}, {day}')
                        ->required()
                        ->columnSpanFull(),

                    TextInput::make('invoice_numbering_pad_length')
                        ->label('Pad Zeros')
                        ->numeric()
                        ->minValue(1)
                        ->required()
                        ->columnSpan(6),

                    TextInput::make('invoice_numbering_start_number')
                        ->label('Starting Number')
                        ->numeric()
                        ->minValue(1)
                        ->required()
                        ->columnSpan(6),
                ])
                ->columns(12),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Setting::set('invoice_numbering_format', $data['invoice_numbering_format'] ?? '{prefix}-{number}/{year}');
        Setting::set('invoice_numbering_pad_length', (int) ($data['invoice_numbering_pad_length'] ?? 3));
        Setting::set('invoice_numbering_start_number', (int) ($data['invoice_numbering_start_number'] ?? 1));

        Notification::make()
            ->success()
            ->title('Settings saved successfully.')
            ->send();
    }
}
