<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PaymentTypeEnum: string implements HasLabel, HasColor
{
    case INCOME = 'income';
    case EXPENSE = 'expense';

    public function getLabel(): string
    {
        return match($this) {
            self::INCOME => 'Uplata',
            self::EXPENSE => 'Isplata',
        };
    }

    public function getColor(): string|array|null
    {
        return match($this) {
            self::INCOME => 'success',
            self::EXPENSE => 'danger',
        };
    }
}
