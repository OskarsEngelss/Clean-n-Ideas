<?php
use App\Models\User;
use Illuminate\Support\Facades\Event;

test('Lietotājam ir iespējams izveidot jaunu ierakstu', function () {
    Event::fake();

    User::factory()->computerTester()->create();

    $page = visit('/');

    $page->click('.sign-in-button')
        ->assertSee('Sign in')
        ->fill('email', 'computertester@gmail.com')
        ->fill('password', 'password')
        // ->screenshot(filename: 'before-login-submit')
        ->click('.submit-button')
        ->click('Your Experiences')
        ->click('.your-experiences-share-new-button')
        ->assertSee('Outside')
        ->fill('title', 'Cleaning dirty dishes easily')
        ->click('#category-input')
        ->click('Housekeeping')
        ->assertValue('input[name=category]', 'Housekeeping')
        ->fill('description', 'Ever wondered how to clean dirty dishes after leaving them out for too long? Or maybe you just want to know how to speed up the cleaning process? Well this is the tutorail for you! Follow my guide and it will be very easy :)')
        ->fill('#tutorial-editor', 'Step number one is getting your dirty dishes wet and letting them soak up the water for a bit. After that you want to take your sponge, put dish washing liquid on it and start scrubbing. Because we wet the dishes earlier, everything should come off quickly and easily :)')
        ->click('Publish')
        ->assertSee('Cleaning dirty dishes easily');
});