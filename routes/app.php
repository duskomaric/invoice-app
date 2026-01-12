<?php

use App\Http\Controllers\App\ArticleController;
use App\Http\Controllers\App\ClientController;
use App\Http\Controllers\App\CompanyController;
use App\Http\Controllers\App\ContractController;
use App\Http\Controllers\App\DashboardController;
use App\Http\Controllers\App\InvoiceController;
use App\Http\Controllers\App\PaymentController;
use App\Http\Controllers\App\ProformaController;
use App\Http\Controllers\App\QuoteController;
use App\Http\Controllers\App\ReportController;
use App\Http\Controllers\App\SettingsController;
use App\Http\Controllers\App\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('templates')->name('templates.')->group(function () {
    Route::get('/invoice1', fn() => view('app.invoice1.index'))->name('invoice1.index');
    Route::get('/invoice1/create', fn() => view('app.invoice1.create'))->name('invoice1.create');
    Route::get('/invoice1/show', fn() => view('app.invoice1.show'))->name('invoice1.show');

    Route::get('/invoice2', fn() => view('app.invoice2.index'))->name('invoice2.index');

    Route::get('/invoice3', fn() => view('app.invoice3.index'))->name('invoice3.index');
    Route::get('/invoice3/create', fn() => view('app.invoice3.create'))->name('invoice3.create');
    Route::get('/invoice3/{id}', fn() => view('app.invoice3.show'))->name('invoice3.show');

    Route::get('/invoice4', fn() => view('app.invoice4.index'))->name('invoice4.index');
    Route::get('/invoice4/create', fn() => view('app.invoice4.create'))->name('invoice4.create');
    Route::get('/invoice4/{id}', fn() => view('app.invoice4.show'))->name('invoice4.show');
    Route::get('/invoice4/{id}/edit', fn() => view('app.invoice4.edit'))->name('invoice4.edit');

    Route::get('/invoice5', fn() => view('app.invoice5.index'))->name('invoice5.index');
    Route::get('/invoice5/create', fn() => view('app.invoice5.create'))->name('invoice5.create');
    Route::get('/invoice5/{id}', fn() => view('app.invoice5.show'))->name('invoice5.show');
    Route::get('/invoice5/{id}/edit', fn() => view('app.invoice5.edit'))->name('invoice5.edit');
});

Route::middleware(['auth', 'verified'])->prefix('app')->name('app.')->group(function () {

    Route::get('/', [CompanyController::class, 'select'])->name('company.select');
    Route::post('/switch-company/{company}', [CompanyController::class, 'switch'])->name('company.switch');

    Route::middleware(['App\Http\Middleware\SetCurrentCompany'])
        ->prefix('{company}')
        ->group(function () {

            Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

            Route::resource('invoices', InvoiceController::class);
            Route::post('invoices/{invoice}/send', [InvoiceController::class, 'send'])->name('invoices.send');
            Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('invoices.pdf');

            Route::resource('quotes', QuoteController::class);
            Route::post('quotes/{quote}/convert-to-proforma', [QuoteController::class, 'convertToProforma'])->name('quotes.convert-to-proforma');
            Route::post('quotes/{quote}/convert-to-invoice', [QuoteController::class, 'convertToInvoice'])->name('quotes.convert-to-invoice');

            Route::resource('proformas', ProformaController::class);
            Route::post('proformas/{proforma}/convert-to-invoice', [ProformaController::class, 'convertToInvoice'])->name('proformas.convert-to-invoice');

            Route::resource('contracts', ContractController::class);
            Route::post('contracts/{contract}/convert-to-invoice', [ContractController::class, 'convertToInvoice'])->name('contracts.convert-to-invoice');

            Route::resource('clients', ClientController::class);

            Route::resource('articles', ArticleController::class);

            Route::resource('payments', PaymentController::class);

            Route::resource('users', UserController::class);

            Route::prefix('reports')->name('reports.')->group(function () {
                Route::get('/', [ReportController::class, 'index'])->name('index');
                Route::get('/income-book', [ReportController::class, 'incomeBook'])->name('income-book');
            });

            Route::prefix('settings')->name('settings.')->group(function () {
                Route::get('/', [SettingsController::class, 'index'])->name('index');
                Route::get('/company', [SettingsController::class, 'company'])->name('company');
                Route::put('/company', [SettingsController::class, 'updateCompany'])->name('company.update');
                Route::get('/invoice', [SettingsController::class, 'invoice'])->name('invoice');
                Route::put('/invoice', [SettingsController::class, 'updateInvoice'])->name('invoice.update');
                Route::get('/currencies', [SettingsController::class, 'currencies'])->name('currencies');
                Route::post('/currencies', [SettingsController::class, 'storeCurrency'])->name('currencies.store');
                Route::delete('/currencies/{currency}', [SettingsController::class, 'deleteCurrency'])->name('currencies.destroy');

                Route::get('/bank-accounts', [SettingsController::class, 'bankAccounts'])->name('bank-accounts');
                Route::post('/bank-accounts', [SettingsController::class, 'storeBankAccount'])->name('bank-accounts.store');
                Route::put('/bank-accounts/{bankAccount}', [SettingsController::class, 'updateBankAccount'])->name('bank-accounts.update');
                Route::delete('/bank-accounts/{bankAccount}', [SettingsController::class, 'deleteBankAccount'])->name('bank-accounts.destroy');
                Route::get('/email-templates', [SettingsController::class, 'emailTemplates'])->name('email-templates');
                Route::get('/email-signatures', [SettingsController::class, 'emailSignatures'])->name('email-signatures');
                
                Route::get('/fiscalization', [SettingsController::class, 'fiscalization'])->name('fiscalization');
                Route::get('/appearance', [SettingsController::class, 'appearance'])->name('appearance');
                Route::get('/email', [SettingsController::class, 'email'])->name('email');
                Route::get('/notifications', [SettingsController::class, 'notifications'])->name('notifications');
                Route::get('/roles', [SettingsController::class, 'roles'])->name('roles');
            });
        });
});
