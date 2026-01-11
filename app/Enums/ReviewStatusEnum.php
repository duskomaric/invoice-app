<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ReviewStatusEnum: string implements HasLabel, HasColor
{
    case PENDING = 'pending';
    case REVIEWED = 'reviewed';
    case AUTO = 'auto';

    public function getLabel(): string
    {
        return match($this) {
            self::PENDING => 'Čeka pregled',
            self::REVIEWED => 'Pregledano',
            self::AUTO => 'Automatski',
        };
    }

    public function getColor(): string|array|null
    {
        return match($this) {
            self::PENDING => 'warning',
            self::REVIEWED => 'success',
            self::AUTO => 'info',
        };
    }
}
