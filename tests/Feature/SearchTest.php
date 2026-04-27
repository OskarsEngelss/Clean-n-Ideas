<?php
use Database\Seeders\ExperienceSeeder;
use Illuminate\Support\Facades\Event;
use App\Models\Experience;
use App\Models\TutorialMedia;
use App\Models\TutorialOutsideLink;
use App\Models\User;
use App\Models\TutorialList;

test('Lietotājs spēj meklēt pamācības pēc nosaukuma, kategorijas un/vai apraksta, kā arī lietotājus pēc vārda un apraksta', function () {
    // 1. Sagatavošanās: tiek simulēti notikumi (Events) un izveidota nepieciešamā direktorija failu augšupielādei
    Event::fake();
    Storage::disk('public')->makeDirectory('uploads');

    // 2. Testa datu izveide: tiek izveidots testa lietotājs un pamācības, kas tiks izmantotas meklēšanas pārbaudei
    $user = User::factory()->computerTester()->create();
    TutorialList::create([
        'user_id' => $user->id,
        'name' => 'Favourites',
        'is_favourite' => true,
        'is_public' => false,
    ]);

    // Masīvs ar testa pamācībām, kas tiks izmantotas meklēšanas pārbaudei
    $experiences = [
        [
            'title' => 'How to clean dirty laptop fans',
            'category' => 'Electronics',
            'description' => 'This is how you clean out the clogged up computer fans!',
            'visibility' => 'Public',
            'tutorial' => '<p>Hello!! :)</p>',
        ],
        [
            'title' => 'Clean shelves easy',
            'category' => 'Furniture',
            'description' => 'Dust is for losers??',
            'visibility' => 'Public',
            'tutorial' => '<p>Hello!! :)</p>',
        ],
        [
            'title' => 'Simply clean windshield tutorial',
            'category' => 'Vehicles',
            'description' => 'After following my guide, your windshield is going to be so clean, its not even funny hahahah!',
            'visibility' => 'Public',
            'tutorial' => '<p>Hello!! :)</p>',
        ],
    ];

    // Cikls, kas datubāzē saglabā iepriekš definētās pamācības
    foreach ($experiences as $data) {
        $experience = $user->experiences()->create([
            'title' => $data['title'],
            'category' => $data['category'],
            'description' => $data['description'],
            'tutorial' => $data['tutorial'],
            'visibility' => $data['visibility'],
            'slug' => Str::slug($data['title']) . '-' . uniqid(),
            'thumbnail' => 'images/defaults/' . mb_strtolower($data['category'], 'UTF-8') . '-default-thumbnail.webp',
        ]);
    }

    // 3. Testa izpilde: tiek atvērta sākumlapa
    $page = visit('/');

    // Meklēšanas pārbaude pēc pamācības nosaukuma ("Laptop")
    $page->type('q', 'Laptop')
        ->press('#search-form-button')
        ->assertSee('How to clean dirty laptop fans')
        ->screenshot(filename: 'check')
        
        // Meklēšanas pārbaude pēc pamācības apraksta atslēgvārda ("dust")
        ->type('q', 'dust')
        ->press('#search-form-button')
        ->assertSee('Clean shelves easy')
        ->screenshot(filename: 'check-2')
        
        // Meklēšanas pārbaude pēc pamācības kategorijas ("vehicles")
        ->type('q', 'vehicles')
        ->press('#search-form-button')
        ->assertSee('Simply clean windshield tutorial')
        ->screenshot(filename: 'check-3')
        
        // Meklēšanas pārbaude pēc lietotāja vārda ("computer")
        ->type('q', 'computer')
        ->press('#search-form-button')
        ->assertSee('Computer Tester')
        ->screenshot(filename: 'check-4')
        
        // Meklēšanas pārbaude pēc lietotāja apraksta atslēgvārda ("testing")
        ->type('q', 'testing')
        ->press('#search-form-button')
        ->assertSee('Computer Tester')
        ->screenshot(filename: 'check-5');
});