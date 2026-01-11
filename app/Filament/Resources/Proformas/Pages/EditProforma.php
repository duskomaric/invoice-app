<?php

namespace App\Filament\Resources\Proformas\Pages;

use App\Filament\Resources\Invoices\InvoiceResource;
use App\Filament\Resources\Proformas\ProformaResource;
use App\Services\DocumentConversionService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProforma extends EditRecord
{
    protected static string $resource = ProformaResource::class;

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
                    $invoice = $service->convertProformaToInvoice($this->record);
                    
                    $this->redirect(InvoiceResource::getUrl('edit', ['record' => $invoice]));
                }),
        ];
    }
}
