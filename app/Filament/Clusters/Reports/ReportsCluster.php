<?php

namespace App\Filament\Clusters\Reports;

use App\Enums\ModuleEnum;
use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

class ReportsCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static string|null|\UnitEnum $navigationGroup = 'Management';

    protected static ?string $navigationLabel = 'Reports';

    public static function shouldRegisterNavigation(): bool
    {
        return ModuleEnum::Reports->isEnabled();
    }
}
