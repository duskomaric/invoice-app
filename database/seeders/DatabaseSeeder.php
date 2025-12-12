<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Enums\UserStatus;
use App\Models\Article;
use App\Models\Client;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use App\Enums\PermissionEnum;
use App\Models\Permission;
use App\Models\PermissionRoleEnum;

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
                'role' => RoleEnum::Administrator->value,
                'permission_id' => $permission->id,
            ]);
        }

        // 1. Create Companies
        $companies = collect([
            Company::create(['name' => 'Tech Corp', 'slug' => 'tech-corp']), // ID 1
            Company::create(['name' => 'Design Studio', 'slug' => 'design-studio']), // ID 2
            Company::create(['name' => 'Consulting Ltd', 'slug' => 'consulting-ltd']), // ID 3
        ]);

        // 2. Create Users as requested

        // Super Admin - Has access to ALL companies
        $admin = User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => 'admin', // Will be hashed by model cast? user says "admin", User model casts password => hashed?
            // If model casts 'password' => 'hashed', then assigning plain text is automatically hashed.
            // But let's verify User model casts. Step 1889: 'password' => 'hashed'. Yes.
            'role' => RoleEnum::SuperAdmin,
            'status' => UserStatus::ACTIVE,
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
            'status' => UserStatus::ACTIVE,
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
            'status' => UserStatus::ACTIVE,
            'email_verified_at' => now(),
        ]);
        $user2->companies()->attach([1, 2]);

        // 3. Seed Data per Company
        foreach ($companies as $company) {
            $this->command->info("Seeding data for company: {$company->name}");

            // Create Clients
            $clients = Client::factory()->count(10)->create([
                'company_id' => $company->id
            ]);

            // Create Articles
            $articles = Article::factory()->count(10)->create([
                'company_id' => $company->id
            ]);

            // Create Invoices
            Invoice::factory()->count(5)->create([
                'company_id' => $company->id,
                'client_id' => $clients->random()->id,
            ])->each(function (Invoice $invoice) use ($articles) {
                // Create Items
                InvoiceItem::factory()->count(rand(1, 5))->create([
                    'invoice_id' => $invoice->id,
                    'article_id' => $articles->random()->id,
                    'unit_price' => rand(1000, 10000), // override random logic if needed
                ]);
            });
        }
    }
}
