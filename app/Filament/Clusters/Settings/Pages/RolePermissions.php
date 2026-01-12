<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Filament\Clusters\Settings\SettingsCluster;
use App\Models\Permission;
use App\Models\PermissionRoleEnum;
use BackedEnum;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;

class RolePermissions extends Page
{
    use InteractsWithForms;

    protected static ?string $cluster = SettingsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLockClosed;

    protected static ?string $navigationLabel = 'Role Permissions';

    protected static string|\UnitEnum|null $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 9;

    protected string $view = 'filament.pages.settings';

    public ?string $selectedRole = null;

    public array $permissions = [];

    public function mount(): void
    {
        $this->selectedRole = RoleEnum::SuperAdmin->value;
        $this->loadPermissionsForRole($this->selectedRole);

        $this->form->fill([
            'selectedRole' => $this->selectedRole,
            'permissions' => $this->permissions,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('Permissions')
                ->description('Assign and manage permissions for each role.')
                ->schema($this->buildPermissionsFields()),
        ];
    }

    protected function buildPermissionsFields(): array
    {
        $fields = [];

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

        $fields[] = Placeholder::make('permissions_text')
            ->label('Permissions Info')
            ->helperText('Permissions control the user\'s access to different sections and features of the application.');

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

        $this->form->fill([
            'selectedRole' => $this->selectedRole,
            'permissions' => $this->permissions,
        ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $role = $data['selectedRole'] ?? null;
        if (! $role) {
            return;
        }

        PermissionRoleEnum::where('role', $role)->delete();

        $selectedPermissionNames = array_keys(array_filter($data['permissions'] ?? []));
        if (! empty($selectedPermissionNames)) {
            $permissionIdsByName = Permission::whereIn('name', $selectedPermissionNames)
                ->pluck('id', 'name')
                ->toArray();

            $rows = collect($selectedPermissionNames)
                ->map(fn ($permissionName) => [
                    'role' => $role,
                    'permission_id' => $permissionIdsByName[$permissionName] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
                ->filter(fn ($row) => (bool) $row['permission_id'])
                ->values()
                ->toArray();

            if (! empty($rows)) {
                PermissionRoleEnum::insert($rows);
            }
        }

        $this->loadPermissionsForRole($role);

        Notification::make()
            ->success()
            ->title('Settings saved successfully.')
            ->send();
    }
}
