<?php

use App\Enums\RoleEnum;
use App\Models\Article;
use App\Models\User;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create(['role' => RoleEnum::SuperAdmin]));
});

it('can render article list page', function () {
    livewire(\App\Filament\Resources\Articles\Pages\ListArticles::class)
        ->assertSuccessful();
});

it('can create article', function () {
    livewire(\App\Filament\Resources\Articles\Pages\CreateArticle::class)
        ->fillForm([
            'name' => 'Test Article',
            'description' => 'Test description',
            'price' => 5000,
            'is_active' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Article::count())->toBe(1)
        ->and(Article::first()->name)->toBe('Test Article')
        ->and(Article::first()->price)->toBe(500000); // 50.00 in input = 5000 cents, but MoneyInput multiplies by 100
});

it('can edit article', function () {
    $article = Article::factory()->create([
        'name' => 'Original Name',
        'price' => 1000,
    ]);

    livewire(\App\Filament\Resources\Articles\Pages\EditArticle::class, [
        'record' => $article->id,
    ])
        ->fillForm([
            'name' => 'Updated Name',
            'price' => 2000,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $article->refresh();

    expect($article->name)->toBe('Updated Name')
        ->and($article->price)->toBe(200000); // 20.00 in input = 2000 cents, MoneyInput * 100
});

it('validates required fields', function () {
    livewire(\App\Filament\Resources\Articles\Pages\CreateArticle::class)
        ->fillForm([
            'name' => '',
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
});
