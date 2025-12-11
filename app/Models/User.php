<?php

namespace App\Models;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Enums\UserStatus;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser, HasName, MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected static array $rolePermissionsCache = [];

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'email_verified_at',
        'invitation_code',
        'password',
        'role',
        'status',
        'last_seen_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_seen_at' => 'datetime',
        'password' => 'hashed',
        'role' => RoleEnum::class,
        'status' => UserStatus::class,
    ];

    public function hasPermission(PermissionEnum $permission): bool
    {
        // SuperAdmin has all permissions
        if ($this->role === RoleEnum::SuperAdmin) {
            return true;
        }

        if (! isset(self::$rolePermissionsCache[$this->role->value])) {
            self::$rolePermissionsCache[$this->role->value] = PermissionRoleEnum::where('role', $this->role->value)
                ->with('permission')
                ->get()
                ->pluck('permission.name')
                ->toArray();
        }

        return in_array($permission->value, self::$rolePermissionsCache[$this->role->value], true);
    }

    public function getFilamentName(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->status === UserStatus::ACTIVE;
    }
}
