<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case DASHBOARD_VIEW = 'dashboard:view';

    case USER_CREATE = 'user:create';
    case USER_EDIT = 'user:edit';
    case USER_LIST = 'user:list';
    case USER_VIEW = 'user:view';
    case USER_DELETE = 'user:delete';

    public function publicName(): string
    {
        return match ($this) {
            self::DASHBOARD_VIEW => 'View Dashboard',

            self::USER_CREATE => 'Create Users',
            self::USER_EDIT => 'Edit Users',
            self::USER_LIST => 'List Users',
            self::USER_VIEW => 'View User Details',
            self::USER_DELETE => 'Delete Users',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::DASHBOARD_VIEW => 'Allows viewing the dashboard.',

            self::USER_CREATE => 'Allows the user to create new users.',
            self::USER_EDIT => 'Allows the user to edit users (including roles/permissions).',
            self::USER_LIST => 'Allows the user to view users list.',
            self::USER_VIEW => 'Allows the user to view user details.',
            self::USER_DELETE => 'Allows the user to soft-delete users.',
        };
    }

    public function group(): string
    {
        return match ($this) {
            self::DASHBOARD_VIEW => 'Dashboard',
            self::USER_CREATE, self::USER_EDIT, self::USER_LIST, self::USER_VIEW, self::USER_DELETE => 'Users',
        };
    }
}
