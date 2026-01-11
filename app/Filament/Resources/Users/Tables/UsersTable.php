<?php

namespace App\Filament\Resources\Users\Tables;

use App\Enums\RoleEnum;
use App\Enums\UserStatusEnum;
use App\Models\CompanySetting;
use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('first_name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('last_name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->copyable()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('last_seen_at')
                    ->since()
                    ->dateTimeTooltip('M d, Y H:i:s', 'Europe/Sarajevo')
                    ->sortable()
                    ->color(function ($record) {
                        $lastSeen = Carbon::parse($record->last_seen_at);
                        $diffInHours = $lastSeen->diffInHours(now());

                        return match (true) {
                            $diffInHours > 24 => 'danger',
                            $diffInHours > 12 => 'warning',
                            default => 'success',
                        };
                    }),
                TextColumn::make('status')
                    ->formatStateUsing(fn ($state) => $state->getLabel())
                    ->color(fn ($state) => $state->getColor())
                    ->badge()
                    ->sortable(),

                TextColumn::make('role')
                    ->formatStateUsing(fn ($state) => $state->getLabel())
                    ->color(fn ($state) => $state->getColor())
                    ->badge()
                    ->sortable(),

                IconColumn::make('email_verified_at')
                    ->label('Verified')
                    ->boolean()
                    ->state(fn ($record) => $record->email_verified_at !== null)
                    ->tooltip(fn ($record) => $record->email_verified_at
                        ? $record->email_verified_at->format('d.m.Y H:i:s')
                        : 'Not verified'
                    )
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime('d.m.Y H:i', 'Europe/Sarajevo')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime('d.m.Y H:i', 'Europe/Sarajevo')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(function () {
                        return collect(UserStatusEnum::cases())->mapWithKeys(function ($status) {
                            return [$status->value => $status->getLabel()];
                        })->toArray();
                    }),

                SelectFilter::make('role')
                    ->options(function () {
                        return collect(RoleEnum::cases())->mapWithKeys(function ($role) {
                            return [$role->value => $role->getLabel()];
                        })->toArray();
                    }),

                SelectFilter::make('verified')
                    ->label('Email Verified')
                    ->options([
                        'verified' => 'Verified',
                        'unverified' => 'Unverified',
                    ])
                    ->query(function ($query, $value) {
                        if ($value === 'verified') {
                            $query->whereNotNull('email_verified_at');
                        } elseif ($value === 'unverified') {
                            $query->whereNull('email_verified_at');
                        }
                    }),
            ])
            ->defaultSort('created_at', 'desc')
//            ->paginated(CompanySetting::get('pagination'))
            ->defaultPaginationPageOption(CompanySetting::get('default_pagination_option'))
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
