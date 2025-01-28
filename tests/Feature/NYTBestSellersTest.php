<?php

use Illuminate\Support\Facades\Http;

test('can fetch bestsellers', function () {
    Http::fake([
        'api.nytimes.com/*' => Http::response([
            'status' => 'OK',
            'results' => [
                [
                    'title' => 'Test Book',
                    'author' => 'Test Author',
                    'isbn' => ['1234567890'],
                ],
            ],
        ], 200),
    ]);

    $response = $this->getJson('/api/v1/nyt/best-sellers');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'status',
            'results' => [
                '*' => [
                    'title',
                    'author',
                    'isbn',
                ],
            ],
        ]);
});

test('validates negative offset parameter', function () {
    $response = $this->getJson('/api/v1/nyt/best-sellers?offset=-1');

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['offset']);
});

test('accepts valid offset parameter', function () {
    Http::fake([
        'api.nytimes.com/*' => Http::response([
            'status' => 'OK',
            'results' => [],
        ], 200),
    ]);

    $response = $this->getJson('/api/v1/nyt/best-sellers?offset=20');

    $response->assertStatus(200);
});

test('accepts valid isbn array parameter', function () {
    Http::fake([
        'api.nytimes.com/*' => Http::response([
            'status' => 'OK',
            'results' => [],
        ], 200),
    ]);

    $response = $this->getJson('/api/v1/nyt/best-sellers?isbn[]=1234567890&isbn[]=0987654321');

    $response->assertStatus(200);

    Http::assertSent(function ($request) {
        return isset($request['isbn']) && is_array($request['isbn']);
    });
});

test('handles nyt api failure', function () {
    Http::fake([
        'api.nytimes.com/*' => Http::response([
            'fault' => ['error' => 'API Key Invalid'],
        ], 401),
    ]);

    $response = $this->getJson('/api/v1/nyt/best-sellers');

    $response->assertStatus(500)
        ->assertJsonStructure([
            'error',
            'message',
        ]);
});
