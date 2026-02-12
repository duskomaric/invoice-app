<?php

namespace App\Filament\Resources\Quotes;

use App\Enums\ModuleEnum;
use App\Filament\Resources\Quotes\Pages\CreateQuote;
use App\Filament\Resources\Quotes\Pages\EditQuote;
use App\Filament\Resources\Quotes\Pages\ListQuotes;
use App\Filament\Resources\Quotes\Schemas\QuoteForm;
use App\Filament\Resources\Quotes\Tables\QuoteTable;
use App\Models\Currency;
use App\Models\Quote;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Resources\Components\Tab;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class QuoteResource extends Resource
{
    protected static ?string $model = Quote::class;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-document-duplicate';
    
    protected static ?string $navigationLabel = 'Ponude';
    
    protected static ?string $modelLabel = 'Ponuda';
    
    protected static ?string $pluralModelLabel = 'Ponude';
    
    protected static ?int $navigationSort = 1;

    public static function shouldRegisterNavigation(): bool
    {
        return ModuleEnum::Quotes->isEnabled();
    }

    public static function form(Schema $schema): Schema
    {
        return QuoteForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QuoteTable::configure($table);
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
                ->badge(Quote::when($tenantId, fn ($q) => $q->where('company_id', $tenantId))
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
            'index' => ListQuotes::route('/'),
            'create' => CreateQuote::route('/create'),
            'edit' => EditQuote::route('/{record}/edit'),
        ];
    }
}
