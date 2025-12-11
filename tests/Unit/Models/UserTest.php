<?php

use App\Models\User;

it('creates valid user via factory', function () {
    $user = User::factory()->create([
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'test@example.com',
    ]);

    $this->assertDatabaseHas('users', [
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'test@example.com',
    ]);
});

it('hashes user password', function () {
    $user = User::factory()->create();

    // Factory uses 'password' as default
    expect($user->password)->not->toBe('password')
        ->and(\Hash::check('password', $user->password))->toBeTrue();
});
