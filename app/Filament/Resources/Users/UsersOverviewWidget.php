<?php

namespace App\Filament\Resources\Users;

use App\Enums\RoleEnum;
use App\Enums\UserStatus;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class UsersOverviewWidget extends StatsOverviewWidget
{
    public function getColumns(): int | array
    {
        return [
            'md' => 4,
            'xl' => 5,
        ];
    }

    protected function getStats(): array
    {
        $total = User::count();
        $active24 = User::where('last_seen_at', '>=', Carbon::now()->subDay())->count();

        // Status counts
        $statusCounts = collect(UserStatus::cases())
            ->mapWithKeys(fn ($status) => [
                $status->value => User::where('status', $status)->count(),
            ]);

        // Role counts
        $roleCounts = collect(RoleEnum::cases())
            ->mapWithKeys(fn ($role) => [
                $role->value => User::where('role', $role)->count(),
            ]);

        return [
            // Total users
            Stat::make('Total Users', $total)
                ->description('All registered users')
                ->color('primary')
                ->icon('heroicon-o-users')
                ->extraAttributes(['class' => 'redishh'])
                ->columnSpan(1),

            // Active last 24h
            Stat::make('Active in 24h', $active24)
                ->description('Recent activity')
                ->color('info')
                ->icon('heroicon-o-bolt')
                ->columnSpan(1),

            // Status widgets
            ...collect(UserStatus::cases())->map(function (UserStatus $status) use ($statusCounts, $total) {
                $count = $statusCounts[$status->value];

                return Stat::make($status->getLabel(), $count)
                    ->description(
                        $total
                            ? round(($count / $total) * 100) . '% of users'
                            : '0%'
                    )
                    ->color($status->getColor())
                    ->icon(match ($status) {
                        UserStatus::ACTIVE => 'heroicon-o-check-circle',
                        UserStatus::PENDING => 'heroicon-o-clock',
                        UserStatus::DEACTIVATED => 'heroicon-o-x-circle',
                    });
            }),

            // Role widgets
            ...collect(RoleEnum::cases())->map(function (RoleEnum $role) use ($roleCounts) {
                $count = $roleCounts[$role->value];

                return Stat::make($role->getLabel(), $count)
                    ->description('Users with this role')
                    ->color($role->getColor())
                    ->icon(match ($role) {
                        RoleEnum::SuperAdmin => 'heroicon-o-shield-check',
                        RoleEnum::Administrator => 'heroicon-o-cog',
                        RoleEnum::Support => 'heroicon-o-lifebuoy',
                        RoleEnum::CallCenter => 'heroicon-o-phone',
                    });
            }),

        ];
    }
}
