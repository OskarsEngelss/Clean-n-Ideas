<?php

use Database\Seeders\ExperienceSeeder;
use Illuminate\Support\Facades\Event;
use App\Models\Experience;
use App\Models\TutorialMedia;
use App\Models\TutorialOutsideLink;
use App\Models\User;
use App\Models\TutorialList;

test('Lietotājs var gan komentēt, gan sniegt atbildi citu komentāriem', function () {
    // 1. Sagatavošanās: tiek simulēti notikumi un izveidota direktorija failu augšupielādei
    Event::fake();
    Storage::disk('public')->makeDirectory('uploads');

    // 2. Testa lietotāja izveide
    $user = User::factory()->computerTester()->create();

    // Masīvs ar testa pamācību
    $experiences = [
        [
            'title' => 'The best technique to washing clothes!!!',
            'category' => 'Housekeeping',
            'description' => 'I am a master at washing clothing and this tutorial is going to help you understand how it all works :)',
            'visibility' => 'Public',
            'tutorial' => '
                <p>
                    To start off, get all of your dirty clothes and pile them up, all tight together. Take it to the washing machine and get ready for magic:
                    <span class="tutorial-textarea-media-wrapper">
                        <img src="/storage/uploads/dirty-clothes.jpg" class="inserted-media">
                    </span>
                </p>
                <div>
                    After you have completed the previous step, throw all of the clothes inside together with a capsule. I use Persil Power Caps (links given of course :)).
                </div>
                <div>
                    Once you are done, turn it on and watch everything you ever wished happen in front of you.
                    <span class="tutorial-textarea-media-wrapper">
                        <video src="/storage/uploads/dirty-clothes.mp4" controls="" class="inserted-media"></video>
                    </span>
                </div>
                <div>
                    Hope this helped, bye bye now.
                </div>
            ',
            'media' => [
                ['file' => 'dirty-clothes.jpg', 'type' => 'image'],
                ['file' => 'dirty-clothes.mp4', 'type' => 'video'],
            ],
            'urls' => [
            ]
        ]
    ];

    // 3. Mediju failu un pamācības saglabāšana
    foreach ($experiences as $data) {
        $mediaRecords = [];
        foreach ($data['media'] as $m) {
            $srcPath = database_path('seeders/uploads/' . $m['file']);

            if (!file_exists($srcPath)) {
                throw new \Exception("Seeder media missing: {$srcPath}");
            }

            // Piešķir unikālu nosaukumu un saglabā failu
            $dest = 'uploads/' . Str::uuid() . '.' . pathinfo($m['file'], PATHINFO_EXTENSION);
            Storage::disk('public')->put($dest, file_get_contents($srcPath));

            $mediaRecords[] = [
                'path' => $dest,
                'type' => $m['type'],
            ];

            // Aizstāj faila nosaukumu pamācības tekstā ar jauno unikālo ceļu
            $data['tutorial'] = str_replace($m['file'], basename($dest), $data['tutorial']);
        }

        // Pamācības ieraksta izveide datubāzē
        $experience = $user->experiences()->create([
            'title' => $data['title'],
            'category' => $data['category'],
            'description' => $data['description'],
            'tutorial' => $data['tutorial'],
            'visibility' => $data['visibility'],
            'slug' => Str::slug($data['title']) . '-' . uniqid(),
            'thumbnail' => 'images/defaults/' . mb_strtolower($data['category'], 'UTF-8') . '-default-thumbnail.webp',
        ]);

        // Mediju failu piesaiste pamācībai
        foreach ($mediaRecords as $m) {
            TutorialMedia::create([
                'tutorial_id' => $experience->id,
                'user_id' => $user->id,
                'type' => $m['type'],
                'path' => $m['path'],
            ]);
        }
    }

    // 4. Testa izpilde: Autentifikācija un komentēšanas pārbaude
    $page = visit('/');

    $page->click('.sign-in-button')
        ->assertSee('Sign in')
        ->fill('email', 'computertester@gmail.com')
        ->fill('password', 'password')
        ->press('.submit-button')
        ->screenshot(filename: 'after-loggin')
        
        // Atver pamācību un atver komentāru sadaļu
        ->click('The best technique to washing clothes!!!')
        ->press('#experience-show-comments-button')
        
        // Pievieno jaunu komentāru
        ->click('#experience-show-comments-popup-input')
        ->fill('#experience-show-comments-popup-input', 'This tutorial was really helpful for me! I even learnt what capsules to buy :O')
        ->click('#experience-show-comments-popup-submit-button')
        ->screenshot(filename: 'after-commenting')
        ->assertSee('capsules')
        
        // Atbild uz tikko izveidoto komentāru (Reply)
        ->press('.experience-show-comment-reply')
        ->fill('#experience-show-comments-popup-input', 'I know right, the video helped me understand better as well!!')
        ->click('#experience-show-comments-popup-submit-button')
        ->screenshot(filename: 'after-replying')
        ->assertSee('understand');
});