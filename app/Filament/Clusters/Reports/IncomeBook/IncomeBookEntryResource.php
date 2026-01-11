<?php

namespace App\Filament\Clusters\Reports\IncomeBook;

use App\Filament\Clusters\Reports\IncomeBook\Pages\CreateIncomeBookEntry;
use App\Filament\Clusters\Reports\IncomeBook\Pages\EditIncomeBookEntry;
use App\Filament\Clusters\Reports\IncomeBook\Pages\ListIncomeBookEntries;
use App\Filament\Clusters\Reports\IncomeBook\Schemas\IncomeBookEntryForm;
use App\Filament\Clusters\Reports\IncomeBook\Tables\IncomeBookEntryTable;
use App\Filament\Clusters\Reports\ReportsCluster;
use App\Models\IncomeBookEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class IncomeBookEntryResource extends Resource
{
    protected static ?string $model = IncomeBookEntry::class;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Knjiga prihoda';

    protected static ?string $modelLabel = 'Knjiga prihoda';

    protected static ?string $pluralModelLabel = 'Knjiga prihoda';

    protected static ?int $navigationSort = 3;

    protected static ?string $cluster = ReportsCluster::class;

    public static function form(Schema $schema): Schema
    {
        return IncomeBookEntryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IncomeBookEntryTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListIncomeBookEntries::route('/'),
            'create' => CreateIncomeBookEntry::route('/create'),
            'edit' => EditIncomeBookEntry::route('/{record}/edit'),
        ];
    }
}
