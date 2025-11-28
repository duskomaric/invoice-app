<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\RoleEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\FusedGroup;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Personal Information')
                    ->columns(2)
                    ->columnSpanFull()
                    ->components([
                        FusedGroup::make()
                            ->label('Name (First & Last)')
                            ->schema([
                                TextInput::make('first_name')
                                    ->label('First Name')
                                    ->placeholder('Enter first name')
                                    ->prefixIcon(Heroicon::User)
                                    ->columnSpan(1),

                                TextInput::make('last_name')
                                    ->label('Last Name')
                                    ->placeholder('Enter last name')
                                    ->columnSpan(1),
                            ])
                            ->columnSpanFull()
                            ->columns(2),

                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->prefixIcon(Heroicon::Envelope)
                            ->helperText('User must verify this email before accessing the panel.')
                            ->columnSpan(2),

                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->required()
                            ->prefixIcon(Heroicon::LockClosed)
                            ->helperText('Leave blank to keep current password.')
                            ->columnSpan(2),

                        Select::make('role')
                            ->label('Role')
                            ->options(
                                collect(RoleEnum::cases())->mapWithKeys(fn (RoleEnum $role) => [
                                    $role->value => $role->getLabel(),
                                ])
                            )
                            ->required()
                            ->searchable()
                            ->placeholder('Select a role')
                            ->prefixIcon(Heroicon::ShieldCheck)
                            ->helperText('User role determines access and permissions.')
                            ->columnSpan(1),
                    ]),
            ]);
    }
}
