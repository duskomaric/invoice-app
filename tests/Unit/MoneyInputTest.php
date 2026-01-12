<?php

use App\Filament\Components\MoneyInput;

it('formats state correctly', function () {
    $component = MoneyInput::make('price');

    // Access the afterStateHydrated closure which wraps the formatStateUsing callback
    $reflection = new \ReflectionClass($component);
    $property = $reflection->getProperty('afterStateHydrated');
    $property->setAccessible(true);
    $hydrateClosure = $property->getValue($component);

    expect($hydrateClosure)->not->toBeNull();

    // Extract the inner callback from the closure's used variables
    $hydrateReflection = new \ReflectionFunction($hydrateClosure);
    $usedVars = $hydrateReflection->getClosureUsedVariables();

    expect($usedVars)->toHaveKey('callback');
    $formatStateUsing = $usedVars['callback'];

    // Test null
    expect($formatStateUsing(null))->toBeNull();

    // Test integer (cents) to formatted string
    // 123456 cents = 1.234,56
    expect($formatStateUsing(123456))->toBe('1.234,56');

    // Test zero
    expect($formatStateUsing(0))->toBe('0,00');

    // Test small number
    // 50 cents = 0,50
    expect($formatStateUsing(50))->toBe('0,50');
});

it('dehydrates state correctly', function () {
    $component = MoneyInput::make('price');

    // Access the dehydrateStateUsing closure directly
    $reflection = new \ReflectionClass($component);
    $property = $reflection->getProperty('dehydrateStateUsing');
    $property->setAccessible(true);
    $dehydrateStateUsing = $property->getValue($component);

    expect($dehydrateStateUsing)->not->toBeNull();

    // Test null
    expect($dehydrateStateUsing(null))->toBeNull();

    // Test formatted string to integer (cents)
    // 1.234,56 -> 123456
    expect($dehydrateStateUsing('1.234,56'))->toBe(123456);

    // Test string without thousands separator
    // 100,00 -> 10000
    expect($dehydrateStateUsing('100,00'))->toBe(10000);

    // Test zero
    expect($dehydrateStateUsing('0,00'))->toBe(0);

    // Test small number
    expect($dehydrateStateUsing('0,50'))->toBe(50);
});

it('has correct mask', function () {
    $component = MoneyInput::make('price');
    $mask = $component->getMask();

    expect($mask)->toBeInstanceOf(\Filament\Support\RawJs::class)
        ->and((string) $mask)->toContain('$money($input');
});

it('is numeric', function () {
    $component = MoneyInput::make('price');
    // numeric() adds 'numeric' validation rule
    expect($component->getValidationRules())->toContain('numeric');
});
