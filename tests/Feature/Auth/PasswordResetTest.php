<?php

test('password reset routes are disabled', function () {
    $this->get('/forgot-password')->assertNotFound();
    $this->post('/forgot-password')->assertNotFound();
    $this->get('/reset-password/token')->assertNotFound();
    $this->post('/reset-password')->assertNotFound();
});
