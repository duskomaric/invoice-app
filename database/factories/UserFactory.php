<?php

namespace Database\Factories;

use App\Enums\RoleEnum;
use App\Enums\UserStatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => fake()->randomElement([fake()->dateTimeBetween('-30 days', 'now'), null]),
            'invitation_code' => Str::uuid(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => RoleEnum::cases()[array_rand(RoleEnum::cases())],
            'status' => UserStatusEnum::cases()[array_rand(UserStatusEnum::cases())],
            'last_seen_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }

    /** Unverified email */
    public function unverified(): static
    {
        return $this->state(fn () => ['email_verified_at' => null]);
    }
}
