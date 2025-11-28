<?php

namespace App\Enums;

enum RoleEnum: string
{
    case Support = 'support';
    case CallCenter = 'call_center';
    case Administrator = 'administrator';
    case SuperAdmin = 'super_admin';

    public function getLabel(): string
    {
        return match ($this) {
            self::Support => 'Support',
            self::CallCenter => 'Call Center',
            self::Administrator => 'Administrator',
            self::SuperAdmin => 'Super Admin',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Support => 'primary',
            self::CallCenter => 'warning',
            self::Administrator => 'success',
            self::SuperAdmin => 'danger',
        };

    }
}
