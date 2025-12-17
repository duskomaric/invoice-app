<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings\SettingsCluster;
use App\Models\Setting;
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

class General extends Page
{
    use InteractsWithForms;

    protected static ?string $cluster = SettingsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAdjustmentsHorizontal;

    protected static ?string $navigationLabel = 'General';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.settings';

    public function mount(): void
    {
        $this->form->fill([
            'pagination' => Setting::get('pagination'),
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
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Setting::set('pagination', $data['pagination'] ?? [10, 25, 50, 100]);
        Setting::set('modal_width', $data['modal_width'] ?? Width::Medium->value);
        Setting::set('default_pagination_option', $data['default_pagination_option'] ?? 10);
        Setting::set('top_navigation', $data['top_navigation'] ?? false);
        Setting::set('primary_color', $data['primary_color'] ?? Color::Blue->value);
        Setting::set('danger_color', $data['danger_color'] ?? Color::Red->value);
        Setting::set('gray_color', $data['gray_color'] ?? Color::Gray->value);
        Setting::set('info_color', $data['info_color'] ?? Color::Blue->value);
        Setting::set('success_color', $data['success_color'] ?? Color::Green->value);
        Setting::set('warning_color', $data['warning_color'] ?? Color::Yellow->value);

        Notification::make()
            ->success()
            ->title('Settings saved successfully.')
            ->send();
    }
}
