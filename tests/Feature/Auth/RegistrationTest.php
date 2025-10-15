<?php

use App\Models\Pegawai;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $pegawai = Pegawai::factory()->create([
        'nopeg' => '12345',
    ]);

    $response = $this->post('/register', [
        'nopeg' => $pegawai->nopeg,
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});
