<?php

test('route / works ', function () {
    $page = visit('/');

    $page->click('.sign-in-button')
        ->click('Register instead')
        ->assertSee('Sign in')
        ->fill('name', 'Cat')
        ->fill('email', '2oscaaar@gmail.com')
        ->fill('password', 'password')
        ->fill('password_confirmation', 'password')
        ->click('.submit-button')
        ->click('#profile-icon')
        ->click('Profile')
        ->assertSee('Catt');
});