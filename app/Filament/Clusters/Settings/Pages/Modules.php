<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Enums\ModuleEnum;
use App\Filament\Clusters\Settings\SettingsCluster;
use App\Models\CompanySetting;
use BackedEnum;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;

class Modules extends Page
{
    use InteractsWithForms;

    protected static ?string $cluster = SettingsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquaresPlus;

    protected static ?string $navigationLabel = 'Modules';

    protected static string|\UnitEnum|null $navigationGroup = 'Company Settings';

    protected static ?int $navigationSort = 0;

    protected string $view = 'filament.pages.settings';

    public array $enabled_modules = [];

    public function mount(): void
    {
        $this->form->fill([
            'enabled_modules' => CompanySetting::get('enabled_modules', ModuleEnum::defaultValues()),
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('Modules')
                ->description('Enable or disable modules. Disabling a module hides it from the menu, but background logic (observers, services) still runs.')
                ->schema([
                    CheckboxList::make('enabled_modules')
                        ->label('Enabled modules')
                        ->options(collect(ModuleEnum::cases())
                            ->mapWithKeys(fn (ModuleEnum $m) => [$m->value => $m->label()])
                            ->toArray())
                        ->columns(2)
                        ->required(),
                ]),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        CompanySetting::set('enabled_modules', array_values($data['enabled_modules'] ?? ModuleEnum::defaultValues()));

        Notification::make()
            ->success()
            ->title('Settings saved successfully.')
            ->send();

        $this->redirect(static::getUrl());
    }
}
