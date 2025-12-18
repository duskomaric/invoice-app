<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings\SettingsCluster;
use App\Models\CompanySetting;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;

class Appearance extends Page
{
    use InteractsWithForms;

    protected static ?string $cluster = SettingsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAdjustmentsHorizontal;

    protected static ?string $navigationLabel = 'Appearance';

    protected static string | \UnitEnum | null $navigationGroup = 'Company Settings';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.settings';

    public array $pagination = [];
    public string $modal_width = '';
    public string $default_pagination_option = '';
    public bool $top_navigation = false;
    public string $primary_color = '';
    public string $danger_color = '';
    public string $gray_color = '';
    public string $info_color = '';
    public string $success_color = '';
    public string $warning_color = '';

    public function mount(): void
    {
        $this->form->fill([
            'pagination' => CompanySetting::get('pagination'),
            'modal_width' => CompanySetting::get('modal_width'),
            'default_pagination_option' => CompanySetting::get('default_pagination_option'),
            'top_navigation' => CompanySetting::get('top_navigation'),
            'primary_color' => CompanySetting::get('primary_color'),
            'danger_color' => CompanySetting::get('danger_color'),
            'gray_color' => CompanySetting::get('gray_color'),
            'info_color' => CompanySetting::get('info_color'),
            'success_color' => CompanySetting::get('success_color'),
            'warning_color' => CompanySetting::get('warning_color'),
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
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
                        ->options(fn () => collect(CompanySetting::get('pagination', []))
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
                        ->default(CompanySetting::get('top_navigation'))
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
                        ->default(CompanySetting::get($key))
                        ->columnSpan(2)
                    )->toArray()
                )->columns(6),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        CompanySetting::set('pagination', $data['pagination'] ?? [10, 25, 50, 100]);
        CompanySetting::set('modal_width', $data['modal_width'] ?? Width::Medium->value);
        CompanySetting::set('default_pagination_option', $data['default_pagination_option'] ?? 10);
        CompanySetting::set('top_navigation', $data['top_navigation'] ?? false);
        CompanySetting::set('primary_color', $data['primary_color'] ?? Color::Blue->value);
        CompanySetting::set('danger_color', $data['danger_color'] ?? Color::Red->value);
        CompanySetting::set('gray_color', $data['gray_color'] ?? Color::Gray->value);
        CompanySetting::set('info_color', $data['info_color'] ?? Color::Blue->value);
        CompanySetting::set('success_color', $data['success_color'] ?? Color::Green->value);
        CompanySetting::set('warning_color', $data['warning_color'] ?? Color::Yellow->value);

        Notification::make()
            ->success()
            ->title('Settings saved successfully.')
            ->send();
    }
}
