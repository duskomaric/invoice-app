<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum DocumentTypeEnum: string implements HasColor, HasLabel
{
    case QUOTE = 'quote';
    case PROFORMA = 'proforma';
    case INVOICE = 'invoice';

    public function getLabel(): string
    {
        return match ($this) {
            self::QUOTE => 'Ponuda',
            self::PROFORMA => 'Predračun',
            self::INVOICE => 'Račun',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::QUOTE => 'gray',
            self::PROFORMA => 'warning',
            self::INVOICE => 'success',
        };
    }
}
