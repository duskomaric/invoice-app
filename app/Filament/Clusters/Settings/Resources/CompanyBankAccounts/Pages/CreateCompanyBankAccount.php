<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Settings\Resources\CompanyBankAccounts\Pages;

use App\Filament\Clusters\Settings\Resources\CompanyBankAccounts\CompanyBankAccountResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCompanyBankAccount extends CreateRecord
{
    protected static string $resource = CompanyBankAccountResource::class;
}
