<?php

namespace App\Filament\Pages;

use App\Models\Company;
use App\Services\EmailTemplateDefaultsService;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\RegisterTenant;
use Filament\Schemas\Schema;

class RegisterCompany extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Register New Company';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name')
                    ->label('Company Name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('identification_number')
                    ->label('JIB (ID Number)')
                    ->maxLength(20),
            ]);
    }

    protected function handleRegistration(array $data): Company
    {
        $company = Company::create($data);

        $company->users()->attach(auth()->user());

        app(EmailTemplateDefaultsService::class)->ensureDefaults($company);

        return $company;
    }
}
