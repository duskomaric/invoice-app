<?php

namespace App\Filament\Resources\Proformas;

use App\Enums\ModuleEnum;
use App\Filament\Resources\Proformas\Pages\CreateProforma;
use App\Filament\Resources\Proformas\Pages\EditProforma;
use App\Filament\Resources\Proformas\Pages\ListProformas;
use App\Filament\Resources\Proformas\Schemas\ProformaForm;
use App\Filament\Resources\Proformas\Tables\ProformaTable;
use App\Models\Currency;
use App\Models\Proforma;
use Filament\Facades\Filament;
use Filament\Resources\Components\Tab;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ProformaResource extends Resource
{
    protected static ?string $model = Proforma::class;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?string $navigationLabel = 'Predračuni';

    protected static ?string $modelLabel = 'Predračun';

    protected static ?string $pluralModelLabel = 'Predračuni';

    protected static ?int $navigationSort = 2;

    public static function shouldRegisterNavigation(): bool
    {
        return ModuleEnum::Proformas->isEnabled();
    }

    public static function form(Schema $schema): Schema
    {
        return ProformaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProformaTable::configure($table);
    }

    public static function getTabs(): array
    {
        $tenantId = Filament::getTenant()?->id;

        $currencies = Currency::when($tenantId, fn ($q) => $q->where('company_id', $tenantId))
            ->orderBy('code')
            ->pluck('code')
            ->toArray();

        $tabs = [
            'all' => Tab::make('All'),
        ];

        foreach ($currencies as $currency) {
            $tabs[$currency] = Tab::make($currency)
                ->modifyQueryUsing(fn ($query) => $query->where('currency', $currency))
                ->badge(Proforma::when($tenantId, fn ($q) => $q->where('company_id', $tenantId))
                    ->where('currency', $currency)
                    ->count());
        }

        return $tabs;
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProformas::route('/'),
            'create' => CreateProforma::route('/create'),
            'edit' => EditProforma::route('/{record}/edit'),
        ];
    }
}
