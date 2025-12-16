<?php

namespace App\Filament\Clusters\Settings\Resources\EmailSignature;

use App\Filament\Clusters\Settings\SettingsCluster;
use App\Models\EmailSignature;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class EmailSignatureResource extends Resource
{
    protected static ?string $model = EmailSignature::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-pencil-square';
    protected static string | \UnitEnum | null $navigationGroup = 'Company Settings';

    protected static ?string $cluster = SettingsCluster::class;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_default')
                    ->label('Set as Default'),
                Forms\Components\RichEditor::make('content')
                    ->label('Signature Content')
                    ->required()
                    ->columnSpanFull()
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'link',
                        'bulletList',
                        'orderedList',
                        'h2',
                        'h3',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_default')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => \App\Filament\Clusters\Settings\Resources\EmailSignature\Pages\ListEmailSignatures::route('/'),
            'create' => \App\Filament\Clusters\Settings\Resources\EmailSignature\Pages\CreateEmailSignature::route('/create'),
            'edit' => \App\Filament\Clusters\Settings\Resources\EmailSignature\Pages\EditEmailSignature::route('/{record}/edit'),
        ];
    }
}
