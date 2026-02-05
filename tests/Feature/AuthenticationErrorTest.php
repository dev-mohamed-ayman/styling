<?php

use App\Models\User;

it('returns handled error when unauthenticated on api route', function () {
    $response = $this->getJson('/api/user/profile');

    $response->assertStatus(401)
             ->assertJson([
                 'success' => false,
                 'message' => 'Unauthorized access',
             ]);
});
