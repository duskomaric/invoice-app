<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ArticleTypeEnum: string implements HasLabel, HasColor
{
    case GOODS = 'goods';
    case SERVICES = 'services';
    case PRODUCTS = 'products';

    public function getLabel(): string
    {
        return match($this) {
            self::GOODS => 'Roba',
            self::SERVICES => 'Usluge',
            self::PRODUCTS => 'Proizvodi',
        };
    }

    public function getColor(): string|array|null
    {
        return match($this) {
            self::GOODS => 'info',
            self::SERVICES => 'success',
            self::PRODUCTS => 'warning',
        };
    }
}
