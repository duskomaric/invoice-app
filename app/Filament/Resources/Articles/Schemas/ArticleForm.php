<?php

namespace App\Filament\Resources\Articles\Schemas;

use App\Models\ExchangeRate;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            /* ROOT GRID */
            Grid::make(12)->schema([

                /* ================= LEFT SIDE (9) ================= */
                Grid::make(12)
                    ->schema([

                        /* BASIC INFO */
                        Section::make('Basic Information')
                            ->icon('heroicon-o-cube')
                            ->description('Article or service details')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Name')
                                    ->required()
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-tag')
                                    ->columnSpan(7),

                                Select::make('unit')
                                    ->label('Unit')
                                    ->options([
                                        'KOM' => 'Piece (KOM)',
                                        'SAT' => 'Hour (SAT)',
                                    ])
                                    ->required()
                                    ->default('KOM')
                                    ->prefixIcon('heroicon-o-scale')
                                    ->columnSpan(3),

                                Select::make('tax_category')
                                    ->label('Tax Category')
                                    ->options([
                                        'F' => '11% (F)',
                                        'N' => '0.00% (N)',
                                        'T' => '2% (T)',
                                        'E' => '6% (E)',
                                        'P' => '40% (P)',
                                    ])
                                    ->required()
                                    ->default('KOM')
                                    ->prefixIcon('heroicon-o-bookmark')
                                    ->columnSpan(2),

                                Textarea::make('description')
                                    ->rows(4)
                                    ->maxLength(65535)
                                    ->placeholder('Optional description shown on invoice')
                                    ->columnSpanFull(),
                            ])
                            ->columns(12)
                            ->columnSpanFull(),

                        /* PRICING & TAX */
                        Section::make('Pricing & Tax')
                            ->icon('heroicon-o-banknotes')
                            ->description('Prices per currency')
                            ->schema([

                                ...collect(Filament::getTenant()->currencies()->pluck('code'))
                                    ->map(function ($currency) {

                                        return TextInput::make("prices.$currency")
                                            ->label("Price ({$currency})")
                                            ->reactive()
                                            ->numeric()
                                            ->mask(RawJs::make('$money($input)'))
                                            ->prefix($currency)

                                            ->hintAction(
                                                Action::make('fill_other_prices')
                                                    ->label('Fill other prices')
                                                    ->icon('heroicon-o-arrows-right-left')
                                                    ->visible(fn (Get $get) => filled($get("prices.$currency")))
                                                    ->action(function (Set $set, Get $get) use ($currency) {
                                                        $state = $get("prices.$currency");
                                                        if (! filled($state)) {
                                                            return;
                                                        }

                                                        $rateToBam = ExchangeRate::where('currency', $currency)
                                                            ->orderByDesc('id')
                                                            ->value('rate_to_bam') ?? 1;

                                                        $bam = $currency === 'BAM'
                                                            ? $state
                                                            : $state * $rateToBam;

                                                        foreach (
                                                            Filament::getTenant()
                                                                ->currencies()
                                                                ->pluck('code') as $cur
                                                        ) {
                                                            if ($cur === $currency) {
                                                                continue;
                                                            }

                                                            $curRate = ExchangeRate::where('currency', $cur)
                                                                ->orderByDesc('id')
                                                                ->value('rate_to_bam') ?? 1;

                                                            $set(
                                                                "prices.$cur",
                                                                round($bam / $curRate, 2)
                                                            );
                                                        }
                                                    })
                                            )

                                            ->formatStateUsing(
                                                fn ($state, Get $get, $record) => $record?->prices_meta[$currency] ?? 0
                                            );
                                    })
                                    ->toArray(),

                            ])
                            ->columns(2)
                            ->columnSpanFull(),

                    ])
                    ->columnSpan(9),

                /* ================= RIGHT SIDE (3) ================= */
                Section::make('Status')
                    ->icon('heroicon-o-check-circle')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Active')
                            ->helperText('Inactive articles cannot be used on invoices')
                            ->default(true),
                    ])
                    ->columnSpan(3),

            ])->columnSpanFull(),

        ]);
    }
}
