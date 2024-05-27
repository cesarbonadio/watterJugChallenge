<?php

test('the application returns a successful response', function () {
    $response = $this->get('/api/test');

    // just a healthcheck test
    expect($response->json()['message'])->toBe('ok');
    $response->assertStatus(200);
});

test('testing the correct steps returned (test correct case 1) x=2;y=10;z=4', function () {
    $response = $this->post('/api/waterjug', [
        'x_capacity' => 2,
        'y_capacity' => 10,
        'z_amount_wanted' => 4
    ]);

    $json_response = $response->json();

    expect(count($json_response['data']))->toBe(4);
    expect(count($json_response['metadata']))->toBe(1);
    expect($json_response['message'])->toBe(
        'result found successfully'
    );

    $expected_steps = [
        [2, 0, 'Fill bucket x'],
        [0, 2, 'Transfer from bucket x to bucket y'],
        [2, 2, 'Fill bucket x'],
        [0, 4, 'Transfer from bucket x to bucket y']
    ];

    foreach ($json_response['data'] as $key => $value) {
        $value = (object)$value;
        expect($value)->toHaveProperties([
            'step' => ($key + 1),
            'bucketX' => $expected_steps[$key][0],
            'bucketY' => $expected_steps[$key][1],
            'action' => $expected_steps[$key][2]
        ]);
    }

    $response->assertStatus(200);
});

test('testing the correct steps returned (test correct case 2) x=5;y=3;z=4', function () {
    $response = $this->post('/api/waterjug', [
        'x_capacity' => 5,
        'y_capacity' => 3,
        'z_amount_wanted' => 4
    ]);

    $json_response = $response->json();

    expect(count($json_response['data']))->toBe(6);
    expect(count($json_response['metadata']))->toBe(1);
    expect($json_response['message'])->toBe(
        'result found successfully'
    );

    $expected_steps = [
        [5, 0, 'Fill bucket x'],
        [2, 3, 'Transfer from bucket x to bucket y'],
        [2, 0, 'Empty bucket y'],
        [0, 2, 'Transfer from bucket x to bucket y'],
        [5, 2, 'Fill bucket x'],
        [4, 3, 'Transfer from bucket x to bucket y']
    ];

    foreach ($json_response['data'] as $key => $value) {
        $value = (object)$value;
        expect($value)->toHaveProperties([
            'step' => ($key + 1),
            'bucketX' => $expected_steps[$key][0],
            'bucketY' => $expected_steps[$key][1],
            'action' => $expected_steps[$key][2]
        ]);
    }

    $response->assertStatus(200);
});


test('testing not possible case (amount greater than the capacity of both two jug)', function () {
    $response = $this->post('/api/waterjug', [
        'x_capacity' => 35,
        'y_capacity' => 45,
        'z_amount_wanted' => 55
    ]);

    $json_response = $response->json();

    expect(count($json_response['data']))->toBe(0);
    expect(count($json_response['metadata']))->toBe(0);
    expect($json_response['message'])->toBe(
        'No solution possible. The amount wanted can\'t be greater than the capacity of both two jugs'
    );
});

test('testing not possible case (not possible computationally)', function () {
    $response = $this->post('/api/waterjug', [
        'x_capacity' => 5,
        'y_capacity' => 5,
        'z_amount_wanted' => 4
    ]);

    $json_response = $response->json();

    expect(count($json_response['data']))->toBe(0);
    expect(count($json_response['metadata']))->toBe(0);
    expect($json_response['message'])->toBe(
        'No solution possible. Computationally, it was impossible to find the result for some reason'
    );
});
