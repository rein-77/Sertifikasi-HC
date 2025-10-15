<?php

test('password confirmation routes are disabled', function () {
    $this->get('/confirm-password')->assertNotFound();
    $this->post('/confirm-password')->assertNotFound();
});
