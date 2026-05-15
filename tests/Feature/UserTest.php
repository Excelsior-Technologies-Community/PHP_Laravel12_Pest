<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('users route returns paginated json', function () {
    User::factory()->count(10)->create();

    $response = $this->getJson('/users');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'data',
                'current_page',
                'last_page',
                'per_page',
                'total'
            ]
        ])
        ->assertJsonCount(5, 'data.data'); 
});

test('search users works with pagination', function () {
    User::factory()->create(['name' => 'John Doe']);
    User::factory()->create(['name' => 'Jane Smith']);

    $response = $this->getJson('/users?search=John');

    $response->assertStatus(200)
             ->assertJsonCount(1, 'data.data')
             ->assertJsonPath('data.data.0.name', 'John Doe');
});

test('search validation prevents long strings', function () {
    $longString = str_repeat('a', 256);
    
    $response = $this->getJson("/users?search={$longString}");

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['search']);
});

test('soft delete and restore user', function () {
    $user = User::factory()->create();

    $this->deleteJson("/users/{$user->id}")
         ->assertStatus(200)
         ->assertJson(['status' => true]);

    expect(User::withTrashed()->find($user->id)->trashed())->toBeTrue();

    $this->postJson("/users/{$user->id}/restore")
         ->assertStatus(200)
         ->assertJson(['status' => true]);

    expect(User::find($user->id)->trashed())->toBeFalse();
});

test('toggle status user', function () {
    $user = User::factory()->create(['status' => true]);

    $this->postJson("/users/{$user->id}/toggle-status")
         ->assertStatus(200)
         ->assertJson(['status' => true]);

    expect((bool) User::find($user->id)->status)->toBeFalse();
});

test('export users returns csv', function () {
    User::factory()->create(['name' => 'Alice', 'email' => 'alice@example.com']);

    $response = $this->get('/users/export');

    $response->assertStatus(200)
             ->assertHeaderContains('Content-Type', 'text/csv')
             ->assertSee('Alice,alice@example.com');
});