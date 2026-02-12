<?php

namespace App\Filament\Resources\Contracts\Pages;

use App\Filament\Resources\Contracts\ContractResource;
use App\Filament\Resources\Invoices\InvoiceResource;
use App\Services\DocumentConversionService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContract extends EditRecord
{
    protected static string $resource = ContractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('convert_to_invoice')
                ->label('Pretvori u fakturu')
                ->icon('heroicon-o-arrow-right')
                ->color('success')
                ->requiresConfirmation()
                ->action(function (DocumentConversionService $service) {
                    $invoice = $service->convertContractToInvoice($this->record);

                    $this->redirect(InvoiceResource::getUrl('edit', ['record' => $invoice]));
                }),
        ];
    }
}
