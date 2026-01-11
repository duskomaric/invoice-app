<?php

namespace App\Enums;

use App\Models\CompanySetting;

enum ModuleEnum: string
{
    case Quotes = 'quotes';
    case Proformas = 'proformas';
    case Payments = 'payments';
    case Reports = 'reports';

    public static function defaultValues(): array
    {
        return array_map(
            fn (self $module) => $module->value,
            self::cases(),
        );
    }

    public function label(): string
    {
        return match ($this) {
            self::Quotes => 'Ponude',
            self::Proformas => 'Predračuni',
            self::Payments => 'Uplate/Isplate',
            self::Reports => 'Reports',
        };
    }

    public function isEnabled(): bool
    {
        $enabled = CompanySetting::get('enabled_modules', self::defaultValues());

        return in_array($this->value, $enabled, true);
    }
}
