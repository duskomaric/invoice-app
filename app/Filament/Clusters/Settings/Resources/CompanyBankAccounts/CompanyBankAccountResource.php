<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Settings\Resources\CompanyBankAccounts;

use App\Filament\Clusters\Settings\Resources\CompanyBankAccounts\Pages\CreateCompanyBankAccount;
use App\Filament\Clusters\Settings\Resources\CompanyBankAccounts\Pages\EditCompanyBankAccount;
use App\Filament\Clusters\Settings\Resources\CompanyBankAccounts\Pages\ListCompanyBankAccounts;
use App\Filament\Clusters\Settings\Resources\CompanyBankAccounts\Schemas\CompanyBankAccountForm;
use App\Filament\Clusters\Settings\Resources\CompanyBankAccounts\Tables\CompanyBankAccountsTable;
use App\Filament\Clusters\Settings\SettingsCluster;
use App\Models\CompanyBankAccount;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CompanyBankAccountResource extends Resource
{
    protected static ?string $model = CompanyBankAccount::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingLibrary;

    protected static string|\UnitEnum|null $navigationGroup = 'Company Settings';

    protected static ?string $cluster = SettingsCluster::class;

    public static function form(Schema $schema): Schema
    {
        return CompanyBankAccountForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompanyBankAccountsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $tenantId = Filament::getTenant()?->id;

        return $tenantId
            ? $query->where('company_id', $tenantId)
            : $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCompanyBankAccounts::route('/'),
            'create' => CreateCompanyBankAccount::route('/create'),
            'edit' => EditCompanyBankAccount::route('/{record}/edit'),
        ];
    }
}
