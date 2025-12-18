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
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;

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
                    ->weight(FontWeight::Bold),
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
                    ->money(
                        fn ($record) => $record->currency, // dynamic currency
                        100
                    )
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
                    ->form(function (Invoice $record) {
                        return [
                            Select::make('template_id')
                                ->label('Template')
                                ->options(\App\Models\EmailTemplate::where('company_id', $record->company_id)->where('type', 'invoice')->pluck('name', 'id'))
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    $template = \App\Models\EmailTemplate::find($state);
                                    if ($template) {
                                        $set('subject', $template->subject);
                                        $set('body', $template->body);
                                    }
                                }),
                            TextInput::make('email_to')
                                ->email()
                                ->required()
                                ->default($record->client->email),
                            TextInput::make('subject')
                                ->required(),
                            RichEditor::make('body')
                                ->required(),
                            Select::make('signature_id')
                                ->label('Signature')
                                ->options(\App\Models\EmailSignature::where('company_id', $record->company_id)->pluck('name', 'id'))
                                ->placeholder('Select a signature to append')
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    $signature = \App\Models\EmailSignature::find($state);
                                    if ($signature) {
                                        $set('signature_preview', $signature->content);
                                    } else {
                                        $set('signature_preview', null);
                                    }
                                }),
                            Placeholder::make('signature_preview_block')
                                ->label('Signature Preview')
                                ->content(fn ($get) => new HtmlString($get('signature_preview') ?? '<span class="text-gray-400 italic">No signature selected</span>'))
                                ->visible(fn ($get) => $get('signature_id') !== null),
                            Hidden::make('signature_preview'),
                            Placeholder::make('attachments')
                                ->label('Attachments')
                                ->content(fn (Invoice $record) => new HtmlString(
                                    '<div class="flex items-center gap-2 text-sm text-gray-600" style="width: 1rem; height: 1rem;">' .
                                    '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>' .
                                    (new \App\Services\InvoiceService())->getPdfFilename($record) .
                                    '</div>'
                                )),
                        ];
                    })
                    ->fillForm(function (Invoice $record) {
                        $defaultTemplate = \App\Models\EmailTemplate::where('company_id', $record->company_id)
                            ->where('type', 'invoice')
                            ->where('is_default', true)
                            ->first();

                        return [
                            'email_to' => $record->client->email,
                            'template_id' => $defaultTemplate?->id,
                            'subject' => $defaultTemplate?->subject,
                            'body' => $defaultTemplate?->body,
                        ];
                    })
                    ->modalHeading('Send Invoice via Email')
                    ->modalSubmitActionLabel('Send Email')
                    ->action(function (array $data, Invoice $record) {
                        $body = $data['body'];

                        if (!empty($data['signature_id'])) {
                            $signature = \App\Models\EmailSignature::find($data['signature_id']);
                            if ($signature) {
                                $body .= "<br><br>" . $signature->content;
                            }
                        }

                        (new \App\Services\InvoiceService())->sendEmail(
                            $record,
                            $data['email_to'],
                            $data['subject'],
                            $body
                        );

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
