<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('User Info')
                    ->columns(2)
                    ->components([
                        TextEntry::make('name')
                            ->label('Full Name')
                            ->getStateUsing(fn ($record) => $record->getFilamentName())
                            ->icon(Heroicon::User)
                            ->placeholder('-'),

                        TextEntry::make('email')
                            ->label('Email Address')
                            ->copyable()
                            ->icon(Heroicon::Envelope)
                            ->placeholder('-'),

                        TextEntry::make('role')
                            ->label('Role')
                            ->getStateUsing(fn ($record) => $record->role?->getLabel())
                            ->color(fn ($record) => $record->role?->getColor() ?? 'primary')
                            ->badge()
                            ->icon(Heroicon::ShieldCheck)
                            ->placeholder('-'),

                        TextEntry::make('status')
                            ->label('Status')
                            ->getStateUsing(fn ($record) => $record->status?->getLabel())
                            ->color(fn ($record) => $record->status?->getColor() ?? 'secondary')
                            ->badge()
                            ->icon(Heroicon::CheckBadge)
                            ->placeholder('-'),
                    ]),

                Section::make('Account Info')
                    ->columns(2)
                    ->components([
                        TextEntry::make('email_verified_at')
                            ->label('Email Verified At')
                            ->dateTime()
                            ->icon(Heroicon::CheckCircle)
                            ->placeholder('-'),

                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime()
                            ->icon(Heroicon::Calendar)
                            ->placeholder('-'),

                        TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime()
                            ->icon(Heroicon::Clock)
                            ->placeholder('-'),
                    ]),
            ]);
    }
}
