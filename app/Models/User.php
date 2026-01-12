<?php

namespace App\Models;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Enums\UserStatusEnum;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

class User extends Authenticatable implements FilamentUser, HasName, HasTenants, MustVerifyEmail
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
        'status' => UserStatusEnum::class,
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
        if ($this->status !== UserStatusEnum::ACTIVE) {
            return false;
        }

        //        if ($this->role === RoleEnum::SuperAdmin) {
        //            return true;
        //        }

        $hasAnyCompany = $this->companies()->exists();

        if (! $hasAnyCompany) {
            return true;
        }

        $hasActiveCompany = $this->companies()
            ->where(function ($query) {
                $query
                    ->whereNull('subscription_ends_at')
                    ->orWhere('subscription_ends_at', '>', now());
            })
            ->exists();

        return $hasActiveCompany;
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class);
    }

    public function getTenants(Panel $panel): array|Collection
    {
        // SuperAdmin sees all companies
        if ($this->role === RoleEnum::SuperAdmin) {
            return $this->companies;
        }

        // Regular users only see companies with valid subscription
        return $this->companies->filter(function ($company) {
            return $company->subscription_ends_at === null || $company->subscription_ends_at->isFuture();
        });
    }

    public function canAccessTenant(\Illuminate\Database\Eloquent\Model $tenant): bool
    {
        $hasAccess = $this->companies->contains($tenant);

        if ($this->role === RoleEnum::SuperAdmin) {
            return $hasAccess;
        }

        // Check subscription
        if ($tenant->subscription_ends_at && $tenant->subscription_ends_at->isPast()) {
            return false;
        }

        return $hasAccess;
    }
}
