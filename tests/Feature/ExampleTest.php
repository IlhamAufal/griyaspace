<?php

test('the login page returns a successful response', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('unauthenticated user is redirected to login', function () {
    $response = $this->get('/');

    $response->assertRedirect('/login');
});
