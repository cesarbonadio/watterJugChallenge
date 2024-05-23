<?php

use App\Services\WatterJugService;

test('that true is true', function () {
    expect(true)->toBeTrue();
});

test('unit-testing the correct steps returned (test correct case 1) x=2;y=10;z=4', function () {
    $result = WatterJugService::waterJugBFS(
        2, // x_capacity
        10, // y_capacity
        4 // z_amount_wanted
    );
    expect(count($result))->toBe(4);

    $expected_steps = [
        [2, 0, 'Fill bucket x'],
        [0, 2, 'Transfer from bucket x to bucket y'],
        [2, 2, 'Fill bucket x'],
        [0, 4, 'Transfer from bucket x to bucket y']
    ];

    foreach ($result as $key => $value) {
        $value = (object)$value;
        expect($value)->toHaveProperties([
            'step' => ($key + 1),
            'bucketX' => $expected_steps[$key][0],
            'bucketY' => $expected_steps[$key][1],
            'action' => $expected_steps[$key][2]
        ]);
    }
});

test('unit-testing the correct steps returned (test correct case 2) x=2;y=100;z=96', function () {
    $result = WatterJugService::waterJugBFS(
        2, // x_capacity
        100, // y_capacity
        96 // z_amount_wanted
    );
    expect(count($result))->toBe(4);

    $expected_steps = [
        [0, 100, 'Fill bucket y'],
        [2, 98, 'Transfer from bucket y to bucket x'],
        [0, 98, 'Empty bucket x'],
        [2, 96, 'Transfer from bucket y to bucket x']
    ];

    foreach ($result as $key => $value) {
        $value = (object)$value;
        expect($value)->toHaveProperties([
            'step' => ($key + 1),
            'bucketX' => $expected_steps[$key][0],
            'bucketY' => $expected_steps[$key][1],
            'action' => $expected_steps[$key][2]
        ]);
    }
});

test('unit-testing not possible case (not possible computationally)', function () {
    $result = WatterJugService::waterJugBFS(
        5, // x_capacity
        5, // y_capacity
        4 // z_amount_wanted
    );
    expect($result)->toBeString();
    expect($result)->toBe(WatterJugService::ERROR_NO_SOLUTION_MESSAGE);
});

test('unit-testing not possible case (amount greater than the capacity of both two jug)', function () {
    $result = WatterJugService::waterJugBFS(
        5, // x_capacity
        5, // y_capacity
        10 // z_amount_wanted
    );
    expect($result)->toBeString();
    expect($result)->toBe(WatterJugService::ERROR_NO_SOLUTION_MESSAGE);
});