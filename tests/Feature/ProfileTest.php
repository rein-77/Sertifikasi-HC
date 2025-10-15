<?php

use App\Models\User;

test('profile page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/profile');

    $response->assertOk();
    $response->assertSee($user->pegawai_nopeg);
    $response->assertSee(optional($user->pegawai)->nama);
});
