<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('users route returns json', function () {
    User::factory()->count(3)->create();
    $response = $this->get('/users');
    $response->assertStatus(200)
             ->assertJsonCount(3, 'data');
});

test('search users works', function () {
    User::factory()->create(['name' => 'John Doe']);
    $response = $this->get('/users?search=John');
    $response->assertJsonCount(1, 'data');
});

test('soft delete and restore user', function () {
    $user = User::factory()->create();
    $this->delete("/users/{$user->id}")->assertJson(['status'=>true]);
    expect(User::withTrashed()->find($user->id)->trashed())->toBeTrue();

    $this->post("/users/{$user->id}/restore")->assertJson(['status'=>true]);
    expect(User::find($user->id)->trashed())->toBeFalse();
});

test('toggle status user', function () {
    $user = User::factory()->create(['status' => true]);
    $this->post("/users/{$user->id}/toggle-status")->assertJson(['status' => true]);

    // Cast to boolean
    expect((bool) User::find($user->id)->status)->toBeFalse();
});

test('export users returns csv', function () {
    User::factory()->create(['name'=>'Alice', 'email'=>'alice@example.com']);
    $response = $this->get('/users/export');
    $response->assertStatus(200)
             ->assertHeaderContains('Content-Type', 'text/csv')
             ->assertSee('Alice,alice@example.com');
});