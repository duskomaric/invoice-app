<?php

namespace App\Filament\Components;

use Filament\Forms\Components\TextInput;
use Filament\Support\RawJs;

class MoneyInput extends TextInput
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->mask(RawJs::make('$money($input, \',\', \'.\', 2)'))
            ->formatStateUsing(function ($state) {
                if ($state === null) {
                    return null;
                }

                return number_format((float) $state / 100, 2, ',', '.');
            })
            ->dehydrateStateUsing(function ($state) {
                if (is_null($state)) {
                    return null;
                }

                if (is_int($state)) {
                    return $state;
                }

                $state = str_replace('.', '', $state); // remove thousand separator
                $state = str_replace(',', '.', $state); // replace comma with dot for decimal

                return (int) round(((float) $state) * 100);
            });
    }
}
