<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Artisan::call('user:create', [
            '--first_name' => 'Admin',
            '--last_name' => 'Adminic',
            '--email' => 'admin@admin.com',
            '--password' => 'admin',
        ]);

        User::factory(10)->create();
        Client::factory(10)->create();
        Article::factory(10)->create();
        Invoice::factory(20)->create();
    }
}
