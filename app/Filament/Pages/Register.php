<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SimplePage;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class Register extends SimplePage
{
    protected string $view = 'filament.pages.register';

    public ?array $data = [];

    public User $invitedUser;

    public string $invitationCode;

    public function mount(): void
    {
        $this->invitationCode = request()->route('user');

        $this->invitedUser = User::where('invitation_code', $this->invitationCode)->firstOrFail();

        if (auth()->check()) {
            redirect()->intended(Filament::getUrl());
        }

        // Pre-fill email from invitation
        $this->form->fill([
            'email' => $this->invitedUser->email,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('first_name')
                    ->label('First Name')
                    ->required()
                    ->maxLength(255)
                    ->autofocus(),

                TextInput::make('last_name')
                    ->label('Last Name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->disabled(), // Email is fixed from invitation

                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required()
                    ->rule(Password::default())
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state)),

                TextInput::make('password_confirmation')
                    ->label('Confirm Password')
                    ->password()
                    ->required()
                    ->same('password'),
            ])
            ->statePath('data');
    }

    public function register()
    {
        $data = $this->form->getState();

        $this->invitedUser->update([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'password' => $data['password'],
            'invitation_code' => null,
            'email_verified_at' => now(),
        ]);

        auth()->login($this->invitedUser);

        return redirect()->intended(Filament::getUrl());
    }

    public function getTitle(): string
    {
        return 'Complete Your Registration';
    }

    public function loginAction(): Action
    {
        return Action::make('login')
            ->link()
            ->label('Sign in to your account')
            ->url(filament()->getLoginUrl());
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('register')
                ->label('Sign up')
                ->submit('register'),
        ];
    }

    protected function mutateFormDataBeforeRegister(array $data): array
    {
        return $data;
    }
}
