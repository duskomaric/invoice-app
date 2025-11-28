<?php

namespace App\Filament\Pages;

use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use League\Uri\Components\Query;

class EditProfile extends BaseEditProfile
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('first_name')->required(),
                TextInput::make('last_name')->required(),
                TextEntry::make('role')
                    ->state(fn ($record) => $record->role?->getLabel())
                    ->color(fn ($record) => $record->role?->getColor())
                    ->badge(),
                TextEntry::make('status')
                    ->state(fn ($record) => $record->status?->getLabel())
                    ->color(fn ($record) => $record->status?->getColor())
                    ->badge(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    protected function sendEmailChangeVerification(Model $record, string $newEmail): void
    {
        if ($record->email === $newEmail) {
            return;
        }

        // Generate verification URL
        $verificationUrl = Filament::getVerifyEmailChangeUrl($record, $newEmail);

        // Extract signature from query string
        parse_str(parse_url($verificationUrl, PHP_URL_QUERY), $query);
        $signature = $query['signature'] ?? null;

        if (! $signature) {
            logger('Email change verification signature missing!');

            return;
        }

        // Store signature in cache
        cache()->put($signature, true, now()->addHour());

        // Generate block URL using same signature
        $blockUrl = Filament::getBlockEmailChangeVerificationUrl($record, $newEmail, $signature);

        // Send notice to old email
        Mail::to($record->email)->send(new \App\Mail\OldEmailNoticeMail($newEmail, $blockUrl));

        // Send verification to new email
        Mail::to($newEmail)->send(new \App\Mail\NewEmailVerifyMail($verificationUrl));

        // Filament toast notification
        $this->getEmailChangeVerificationSentNotification($newEmail)?->send();

        // Prevent immediate email change
        $this->data['email'] = $record->getAttributeValue('email');
    }
}
