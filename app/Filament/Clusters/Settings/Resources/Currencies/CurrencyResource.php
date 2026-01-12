<?php

namespace App\Filament\Clusters\Settings\Resources\Currencies;

use App\Filament\Clusters\Settings\Resources\Currencies\Pages\CreateCurrency;
use App\Filament\Clusters\Settings\Resources\Currencies\Pages\EditCurrency;
use App\Filament\Clusters\Settings\Resources\Currencies\Pages\ListCurrencies;
use App\Filament\Clusters\Settings\Resources\Currencies\Schemas\CurrencyForm;
use App\Filament\Clusters\Settings\Resources\Currencies\Tables\CurrenciesTable;
use App\Filament\Clusters\Settings\SettingsCluster;
use App\Models\Currency;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CurrencyResource extends Resource
{
    protected static ?string $model = Currency::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentCurrencyEuro;

    protected static string|\UnitEnum|null $navigationGroup = 'Company Settings';

    protected static ?string $cluster = SettingsCluster::class;

    public static function form(Schema $schema): Schema
    {
        return CurrencyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CurrenciesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCurrencies::route('/'),
            'create' => CreateCurrency::route('/create'),
            'edit' => EditCurrency::route('/{record}/edit'),
        ];
    }
}
