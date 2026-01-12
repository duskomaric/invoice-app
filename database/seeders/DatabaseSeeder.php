<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Enums\UserStatusEnum;
use App\Models\Article;
use App\Models\Client;
use App\Models\Company;
use App\Models\CompanyBankAccount;
use App\Models\Contract;
use App\Models\ContractItem;
use App\Models\Currency;
use App\Models\EmailSignature;
use App\Models\EmailTemplate;
use App\Models\IncomeBookEntry;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use App\Models\Permission;
use App\Models\PermissionRoleEnum;
use App\Models\Proforma;
use App\Models\ProformaItem;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 0. Seed Permissions & Roles
        $permissions = [];
        foreach (PermissionEnum::cases() as $permissionEnum) {
            $permissions[] = Permission::create([
                'name' => $permissionEnum->value,
                'public_name' => $permissionEnum->publicName(),
                'description' => $permissionEnum->description(),
            ]);
        }

        // Assign ALL permissions to Administrator role
        foreach ($permissions as $permission) {
            PermissionRoleEnum::create([
                'role' => RoleEnum::SuperAdmin->value,
                'permission_id' => $permission->id,
            ]);
        }

        // 1. Create Companies
        $companies = collect([
            Company::create(['name' => 'Tech Corp', 'slug' => 'tech-corp']),
            Company::create(['name' => 'Design Studio', 'slug' => 'design-studio']),
            Company::create(['name' => 'Consulting Ltd', 'slug' => 'consulting-ltd']),
        ]);

        // 2. Create Users

        // Super Admin - Has access to ALL companies
        $admin = User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => 'admin',
            'role' => RoleEnum::SuperAdmin,
            'status' => UserStatusEnum::ACTIVE,
            'email_verified_at' => now(),
        ]);
        $admin->companies()->attach($companies->pluck('id'));

        // Admin 1 - Has access to 1 company (Tech Corp)
        $user1 = User::create([
            'first_name' => 'Admin',
            'last_name' => 'One',
            'email' => 'admin1@admin.com',
            'password' => 'admin',
            'role' => RoleEnum::Administrator,
            'status' => UserStatusEnum::ACTIVE,
            'email_verified_at' => now(),
        ]);
        $user1->companies()->attach($companies->first()->id);

        // Admin 2 - Has access to 2 companies (Tech Corp & Design Studio)
        $user2 = User::create([
            'first_name' => 'Admin',
            'last_name' => 'Two',
            'email' => 'admin2@admin.com',
            'password' => 'admin',
            'role' => RoleEnum::Administrator,
            'status' => UserStatusEnum::ACTIVE,
            'email_verified_at' => now(),
        ]);
        $user2->companies()->attach([1, 2]);

        // 3. Seed Data per Company
        foreach ($companies as $company) {
            $this->command->info("Seeding data for company: {$company->name}");

            // Create Currencies (ensure unique codes per company)
            $currencies = collect([
                Currency::factory()->bam()->create(['company_id' => $company->id]),
                Currency::factory()->eur()->create(['company_id' => $company->id]),
                Currency::factory()->usd()->create(['company_id' => $company->id]),
            ]);

            // Create Bank Accounts
            $bankAccounts = CompanyBankAccount::factory()->count(2)->create([
                'company_id' => $company->id,
            ]);
            // Make first one default
            if ($bankAccounts->first()) {
                $bankAccounts->first()->update(['is_default' => true]);
            }

            // Create Email Templates
            EmailTemplate::factory()->count(3)->create([
                'company_id' => $company->id,
            ]);
            // Make one default
            EmailTemplate::factory()->default()->create([
                'company_id' => $company->id,
                'type' => 'invoice',
            ]);

            // Create Email Signatures
            EmailSignature::factory()->count(2)->create([
                'company_id' => $company->id,
            ]);
            // Make one default
            EmailSignature::factory()->default()->create([
                'company_id' => $company->id,
            ]);

            // Create Clients
            $clients = Client::factory()->count(10)->create([
                'company_id' => $company->id,
            ]);

            // Create Articles
            $articles = Article::factory()->count(15)->create([
                'company_id' => $company->id,
            ]);

            // Create Quotes
            $quotes = Quote::factory()->count(5)->create([
                'company_id' => $company->id,
                'client_id' => fn () => $clients->random()->id,
            ])->each(function (Quote $quote) use ($articles) {
                // Create Quote Items
                QuoteItem::factory()->count(rand(2, 5))->create([
                    'quote_id' => $quote->id,
                    'article_id' => fn () => $articles->random()->id,
                ]);
            });

            // Create Proformas (some from quotes)
            $proformas = Proforma::factory()->count(4)->create([
                'company_id' => $company->id,
                'client_id' => fn () => $clients->random()->id,
            ])->each(function (Proforma $proforma, $index) use ($quotes, $articles) {
                // Some proformas have source quotes
                if ($index < 2 && $quotes->isNotEmpty()) {
                    $proforma->update([
                        'sourceable_type' => Quote::class,
                        'sourceable_id' => $quotes->random()->id,
                    ]);
                }

                // Create Proforma Items
                ProformaItem::factory()->count(rand(2, 5))->create([
                    'proforma_id' => $proforma->id,
                    'article_id' => fn () => $articles->random()->id,
                ]);
            });

            // Create Invoices (some from quotes/proformas)
            $invoices = Invoice::factory()->count(8)->create([
                'company_id' => $company->id,
                'client_id' => fn () => $clients->random()->id,
            ])->each(function (Invoice $invoice, $index) use ($quotes, $proformas, $articles) {
                // Some invoices have source documents
                if ($index < 3 && $quotes->isNotEmpty()) {
                    $invoice->update([
                        'sourceable_type' => Quote::class,
                        'sourceable_id' => $quotes->random()->id,
                    ]);
                } elseif ($index < 5 && $proformas->isNotEmpty()) {
                    $invoice->update([
                        'sourceable_type' => Proforma::class,
                        'sourceable_id' => $proformas->random()->id,
                    ]);
                }

                // Create Invoice Items
                InvoiceItem::factory()->count(rand(2, 6))->create([
                    'invoice_id' => $invoice->id,
                    'article_id' => fn () => $articles->random()->id,
                ]);

                // Attach bank accounts to some invoices
                if (rand(0, 1)) {
                    $invoice->bankAccounts()->attach(
                        CompanyBankAccount::where('company_id', $invoice->company_id)
                            ->inRandomOrder()
                            ->first()?->id
                    );
                }
            });

            // Create Payments (linked to invoices, quotes, proformas)
            $payments = Payment::factory()->count(12)->create([
                'company_id' => $company->id,
                'client_id' => fn () => $clients->random()->id,
            ])->each(function (Payment $payment, $index) use ($invoices, $quotes, $proformas) {
                // Link payments to documents
                if ($index < 5 && $invoices->isNotEmpty()) {
                    $payment->update([
                        'invoice_id' => $invoices->random()->id,
                    ]);
                } elseif ($index < 8 && $quotes->isNotEmpty()) {
                    $payment->update([
                        'quote_id' => $quotes->random()->id,
                    ]);
                } elseif ($index < 10 && $proformas->isNotEmpty()) {
                    $payment->update([
                        'proforma_id' => $proformas->random()->id,
                    ]);
                }
            });

            // Create Contracts
            $contracts = Contract::factory()->count(3)->create([
                'company_id' => $company->id,
                'client_id' => fn () => $clients->random()->id,
            ])->each(function (Contract $contract) use ($articles) {
                // Create Contract Items
                ContractItem::factory()->count(rand(2, 5))->create([
                    'contract_id' => $contract->id,
                    'article_id' => fn () => $articles->random()->id,
                ]);
            });

            // Create Income Book Entries (linked to payments)
            IncomeBookEntry::factory()->count(8)->create([
                'company_id' => $company->id,
                'payment_id' => fn () => $payments->random()->id,
            ])->each(function (IncomeBookEntry $entry, $index) use ($invoices, $quotes, $proformas) {
                // Link to documents
                if ($index < 4 && $invoices->isNotEmpty()) {
                    $entry->update([
                        'invoice_id' => $invoices->random()->id,
                    ]);
                } elseif ($index < 6 && $quotes->isNotEmpty()) {
                    $entry->update([
                        'quote_id' => $quotes->random()->id,
                    ]);
                } elseif ($index < 8 && $proformas->isNotEmpty()) {
                    $entry->update([
                        'proforma_id' => $proformas->random()->id,
                    ]);
                }
            });

            $this->command->info("  ✓ Created {$clients->count()} clients");
            $this->command->info("  ✓ Created {$articles->count()} articles");
            $this->command->info("  ✓ Created {$quotes->count()} quotes");
            $this->command->info("  ✓ Created {$proformas->count()} proformas");
            $this->command->info("  ✓ Created {$invoices->count()} invoices");
            $this->command->info("  ✓ Created {$contracts->count()} contracts");
            $this->command->info("  ✓ Created {$payments->count()} payments");
        }
    }
}
