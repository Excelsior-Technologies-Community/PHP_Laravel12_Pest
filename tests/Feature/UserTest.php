<?php

use App\Models\User;

test('users route returns json', function () {
    // Arrange: create 3 users in test database
    User::factory()->count(3)->create();

    // Act: call the route
    $response = $this->get('/users');

    // Assert: response checks
    $response->assertStatus(200)
             ->assertJson([
                 'status' => true,
                 'message' => 'User List',
             ])
             ->assertJsonCount(3, 'data'); 
});
