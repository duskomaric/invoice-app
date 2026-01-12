<?php

namespace Database\Factories;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionFactory extends Factory
{
    protected $model = Permission::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->slug(),
            'public_name' => fake()->words(2, true),
            'description' => fake()->optional()->sentence(),
        ];
    }
}
