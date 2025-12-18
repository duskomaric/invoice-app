<?php
//
//namespace App\Filament\Clusters\Settings\Pages;
//
//use App\Filament\Clusters\Settings\SettingsCluster;
//use App\Models\CompanySetting;
//use App\Services\InvoiceNumberingService;
//use BackedEnum;
//use Filament\Actions\Action;
//use Filament\Forms\Components\Placeholder;
//use Filament\Forms\Components\TextInput;
//use Filament\Forms\Concerns\InteractsWithForms;
//use Filament\Notifications\Notification;
//use Filament\Pages\Page;
//use Filament\Schemas\Components\Grid;
//use Filament\Schemas\Components\Section;
//use Filament\Schemas\Components\Utilities\Get;
//use Filament\Support\Enums\Width;
//use Filament\Support\Icons\Heroicon;
//
//class InvoiceNumbering extends Page
//{
//    use InteractsWithForms;
//
//    protected static ?string $cluster = SettingsCluster::class;
//
//    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHashtag;
//
//    protected static ?string $navigationLabel = 'Invoice Numbering';
//
//    protected static string | \UnitEnum | null $navigationGroup = 'Company Settings';
//
//    protected static ?int $navigationSort = 4;
//
//    protected string $view = 'filament.pages.settings';
//
//    public string $invoice_numbering_format;
//    public int $invoice_numbering_pad_length;
//    public int $invoice_numbering_start_number;
//
//
//    public function mount(): void
//    {
//        $this->form->fill([
//            'invoice_numbering_format' => (string) CompanySetting::get('invoice_numbering_format', '{prefix}-{number}/{year}'),
//            'invoice_numbering_pad_length' => (int) CompanySetting::get('invoice_numbering_pad_length', 3),
//            'invoice_numbering_start_number' => (int) CompanySetting::get('invoice_numbering_start_number', 1),
//        ]);
//    }
//
//    protected function getFormSchema(): array
//    {
//        $numbering = app(InvoiceNumberingService::class);
//
//        $presets = [
//            '{currency}-{number}/{year}',
//            '{prefix}/{year}/{number}',
//            '{year}-{month}-{number}',
//            '{prefix}/{year}/{month}/{number}',
//            'INV-{number}/{year}',
//        ];
//
//        return [
//            Section::make('Numbering Rules')
//                ->description('Configure how invoice numbers are generated.')
//                ->schema([
//                    TextInput::make('invoice_numbering_format')
//                        ->label('Number Format')
//                        ->helperText('Placeholders: {prefix}, {currency}, {number}, {year}, {month}, {day}. You can also include static text, e.g. INV-{number}/{year}.')
//                        ->required()
//                        ->live()
//                        ->columnSpanFull()
//                        ->hintActions([
//                            Action::make('presets')
//                                ->label('Presets')
//                                ->icon('heroicon-o-squares-2x2')
//                                ->modalWidth(Width::Small)
//                                ->modalSubmitAction(false)
//                                ->schema([
//                                    Grid::make(1)->schema([
//                                        Placeholder::make('p1')
//                                            ->label($presets[0])
//                                            ->content(fn () => $numbering->previewForConfig(
//                                                format: $presets[0],
//                                                padLength: (int) ($this->form->getState()['invoice_numbering_pad_length'] ?? 3),
//                                                startingNumber: (int) ($this->form->getState()['invoice_numbering_start_number'] ?? 1),
//                                                currency: 'EUR',
//                                                date: now(),
//                                            ))
//                                            ->extraAttributes(['class' => 'text-sm text-gray-500'])
//                                            ->hintAction(
//                                                Action::make('use1')
//                                                    ->label('Use')
//                                                    ->action(fn () => $this->form->fill([
//                                                        'invoice_numbering_format' => $presets[0],
//                                                    ]))
//                                            ),
//                                        Placeholder::make('p2')
//                                            ->label($presets[1])
//                                            ->content(fn () => $numbering->previewForConfig(
//                                                format: $presets[1],
//                                                padLength: (int) ($this->form->getState()['invoice_numbering_pad_length'] ?? 3),
//                                                startingNumber: (int) ($this->form->getState()['invoice_numbering_start_number'] ?? 1),
//                                                currency: 'EUR',
//                                                date: now(),
//                                            ))
//                                            ->hintAction(
//                                                Action::make('use2')
//                                                    ->label('Use')
//                                                    ->action(fn () => $this->form->fill([
//                                                        'invoice_numbering_format' => $presets[1],
//                                                    ]))
//                                            ),
//
//                                        Placeholder::make('p3')
//                                            ->label($presets[2])
//                                            ->content(fn () => $numbering->previewForConfig(
//                                                format: $presets[2],
//                                                padLength: (int) ($this->form->getState()['invoice_numbering_pad_length'] ?? 3),
//                                                startingNumber: (int) ($this->form->getState()['invoice_numbering_start_number'] ?? 1),
//                                                currency: 'EUR',
//                                                date: now(),
//                                            ))
//                                            ->hintAction(
//                                                Action::make('use3')
//                                                    ->label('Use')
//                                                    ->action(fn () => $this->form->fill([
//                                                        'invoice_numbering_format' => $presets[2],
//                                                    ]))
//                                            ),
//
//                                        Placeholder::make('p4')
//                                            ->label($presets[3])
//                                            ->content(fn () => $numbering->previewForConfig(
//                                                format: $presets[3],
//                                                padLength: (int) ($this->form->getState()['invoice_numbering_pad_length'] ?? 3),
//                                                startingNumber: (int) ($this->form->getState()['invoice_numbering_start_number'] ?? 1),
//                                                currency: 'EUR',
//                                                date: now(),
//                                            ))
//                                            ->hintAction(
//                                                Action::make('use4')
//                                                    ->label('Use')
//                                                    ->action(fn () => $this->form->fill([
//                                                        'invoice_numbering_format' => $presets[3],
//                                                    ]))
//                                            ),
//
//                                        Placeholder::make('p5')
//                                            ->label($presets[4])
//                                            ->content(fn () => $numbering->previewForConfig(
//                                                format: $presets[4],
//                                                padLength: (int) ($this->form->getState()['invoice_numbering_pad_length'] ?? 3),
//                                                startingNumber: (int) ($this->form->getState()['invoice_numbering_start_number'] ?? 1),
//                                                currency: 'EUR',
//                                                date: now(),
//                                            ))
//                                            ->hintAction(
//                                                Action::make('use5')
//                                                    ->label('Use')
//                                                    ->action(fn () => $this->form->fill([
//                                                        'invoice_numbering_format' => $presets[4],
//                                                    ]))
//                                            ),
//                                    ]),
//                                ]),
//                        ]),
//
//                    TextInput::make('invoice_numbering_pad_length')
//                        ->label('Pad Zeros')
//                        ->numeric()
//                        ->minValue(1)
//                        ->required()
//                        ->live()
//                        ->columnSpan(6),
//
//                    TextInput::make('invoice_numbering_start_number')
//                        ->label('Starting Number')
//                        ->numeric()
//                        ->minValue(1)
//                        ->required()
//                        ->live()
//                        ->columnSpan(6),
//
//                    Placeholder::make('invoice_numbering_preview')
//                        ->label('Next Invoice Number')
//                        ->content(fn (Get $get) => $numbering->previewForConfig(
//                            format: (string) $get('invoice_numbering_format'),
//                            padLength: (int) $get('invoice_numbering_pad_length'),
//                            startingNumber: (int) $get('invoice_numbering_start_number'),
//                            currency: 'EUR',
//                            date: now(),
//                        ))
//                        ->columnSpanFull(),
//                ])
//                ->columns(12),
//        ];
//    }
//
//    public function save(): void
//    {
//        $data = $this->form->getState();
//
//        CompanySetting::set('invoice_numbering_format', $data['invoice_numbering_format'] ?? '{prefix}-{number}/{year}');
//        CompanySetting::set('invoice_numbering_pad_length', (int) ($data['invoice_numbering_pad_length'] ?? 3));
//        CompanySetting::set('invoice_numbering_start_number', (int) ($data['invoice_numbering_start_number'] ?? 1));
//
//        Notification::make()
//            ->success()
//            ->title('Settings saved successfully.')
//            ->send();
//    }
//}
