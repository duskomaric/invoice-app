<?php

namespace App\Console\Commands;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Models\Permission;
use App\Models\PermissionRoleEnum;
use Illuminate\Console\Command;

class SeedPermissionsCommand extends Command
{
    protected $signature = 'seed:permissions';

    protected $description = 'Seed permissions';

    public function handle(): int
    {
        $permissions = PermissionEnum::cases();

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission->value,
                'public_name' => $permission->publicName(),
                'description' => $permission->description(),
            ]);
            $this->info("Permission '{$permission->value}' created.");
        }

        // Assign all permissions to super admin
        foreach (PermissionEnum::cases() as $permission) {
            PermissionRoleEnum::firstOrCreate([
                'role' => RoleEnum::SuperAdmin->value,
                'permission_id' => Permission::where('name', $permission->value)->first()->id,
            ]);
            $this->info("Permission '{$permission->value}' assigned to role 'SuperAdmin'.");
        }

        $this->info('Permissions seeding completed.');

        return self::SUCCESS;
    }
}
