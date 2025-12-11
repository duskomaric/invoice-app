<?php

namespace App\Filament\Resources\Invoices\Tables;

use App\Enums\InvoiceStatus;
use App\Mail\InvoiceMail;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;

class InvoiceTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')
                    ->label('Invoice #')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),
                TextColumn::make('client.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('currency')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'EUR' => 'warning',
                        'BAM' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                IconColumn::make('is_fiscalized')
                    ->boolean(),
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('total')
                    ->money('BAM', 100, 'sr')
                    ->sortable(),
                IconColumn::make('is_recurring')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options(InvoiceStatus::class),
            ])
            ->actions([
                Action::make('pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function (Invoice $record) {
                        return response()->streamDownload(function () use ($record) {
                            echo (new \App\Services\InvoiceService())->generatePdf($record);
                        }, (new \App\Services\InvoiceService())->getPdfFilename($record));
                    }),
                Action::make('email')
                    ->label('Email')
                    ->icon('heroicon-o-envelope')
                    ->requiresConfirmation()
                    ->modalHeading('Send Invoice via Email')
                    ->modalDescription(fn (Invoice $record) => 'Are you sure you want to send this invoice to ' . $record->client->email . '?')
                    ->modalSubmitActionLabel('Yes, send email')
                    ->action(function (Invoice $record) {
                        if (! $record->client->email) {
                            Notification::make()
                                ->title('Client has no email address')
                                ->danger()
                                ->send();
                            return;
                        }

                        (new \App\Services\InvoiceService())->sendEmail($record, $record->client->email);
                        Notification::make()
                            ->title('Invoice sent successfully')
                            ->success()
                            ->send();
                    }),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
