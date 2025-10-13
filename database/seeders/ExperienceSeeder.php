<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Experience;
use App\Models\TutorialMedia;
use App\Models\TutorialOutsideLink;
use App\Models\User;
use App\Models\TutorialList;

class ExperienceSeeder extends Seeder
{
     public function run(): void
    {
        Storage::disk('public')->makeDirectory('uploads');

        $user = User::factory()->computerTester()->create();
        TutorialList::create([
            'user_id' => $user->id,
            'name' => 'Favourites',
            'is_favourite' => true,
            'is_public' => false,
        ]);

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
                    'https://www.drogas.lv/lv/majai/velas-mazgasanai-un-kopsanai/velas-mazgasanas-lidzekli/persil-power-caps-universal-kapsulas-velas-mazgasanai-29gab/p/BP_571639',
                ],
            ],
            [
                'title' => 'Glovebox cleaning - simple - quick - effective - get over it',
                'category' => 'Vehicles',
                'description' => 'Over the years of working with cars, I have learnt the best way to clean these bitches. This is the how and not the why. Get ready',
                'visibility' => 'Public',
                'tutorial' => '
                    <p>
                        Start off by getting in your car and opening the glovebox. A real mess aint it. Get all of that crap out and you can begin:
                        <span class="tutorial-textarea-media-wrapper">
                            <img src="/storage/uploads/car-glovebox.png" class="inserted-media">
                        </span>
                    </p>
                    <div>
                        Get your vacuum cleaner ready and get to blowin. All you have to do is just blow. Keep blowing until its all clean. Nothing a hoover cant achieve. I use Dysons. They are by far the best, links provided.
                    </div>
                    <div>
                        This should be the final result if you did everything correctly
                        <span class="tutorial-textarea-media-wrapper">
                            <img src="/storage/uploads/car-glovebox-clean.jpg" class="inserted-media">
                        </span>
                    </div>
                    <div>
                        Thats all it took, easy and quick.
                        <span class="tutorial-textarea-media-wrapper">
                            <img src="/storage/uploads/more-you-know-meme.jpg" class="inserted-media">
                        </span>
                    </div>
                ',
                'media' => [
                    ['file' => 'car-glovebox.png', 'type' => 'image'],
                    ['file' => 'car-glovebox-clean.jpg', 'type' => 'image'],
                    ['file' => 'more-you-know-meme.jpg', 'type' => 'image'],
                ],
                'urls' => [
                    'https://www.dyson.com/vacuum-cleaners',
                    'https://www.amazon.com/Dyson-Boat-Handheld-Vacuum-Cleaner/dp/B0DLXGDHBX/ref=sr_1_7?dib=eyJ2IjoiMSJ9.MiaKKUIp7McRC0M-7A1yfioJYxszmH9anFfoBbZ54soT3VNsxQ_OB40WyLJIm_zTpvB8dBVnAonL7ig-wOm3PjI9YsacXhrRG9lhIPwZ6cdL2D_iQnQVT6Vb893uhImOGaapw-5GW4rGDxpWzNl-gizplC_vqk4q-uQpAYAUWH9y0LHg00dIgy9f6pRZT0K9IS6doiZTEhoX2Ba8NmWA7fk0fjjyJZUpUJlA6PhPVXU.LheWtb2v-PryNrbuWo_cawGIYtbP4hI-V3VJhjvazRk&dib_tag=se&keywords=dyson+vacuum&qid=1760179228&sr=8-7',
                ],
            ],
        ];

        foreach ($experiences as $data) {
            $mediaRecords = [];
            foreach ($data['media'] as $m) {
                $srcPath = database_path('seeders/uploads/' . $m['file']);

                if (!file_exists($srcPath)) {
                    throw new \Exception("Seeder media missing: {$srcPath}");
                }

                $dest = 'uploads/' . Str::uuid() . '.' . pathinfo($m['file'], PATHINFO_EXTENSION);
                Storage::disk('public')->put($dest, file_get_contents($srcPath));

                $mediaRecords[] = [
                    'path' => $dest,
                    'type' => $m['type'],
                ];

                $data['tutorial'] = str_replace($m['file'], basename($dest), $data['tutorial']);
            }

            $experience = $user->experiences()->create([
                'title' => $data['title'],
                'category' => $data['category'],
                'description' => $data['description'],
                'tutorial' => $data['tutorial'],
                'visibility' => $data['visibility'],
                'slug' => Str::slug($data['title']) . '-' . uniqid(),
                'thumbnail' => 'images/defaults/' . mb_strtolower($data['category'], 'UTF-8') . '-default-thumbnail.webp',
            ]);

            foreach ($mediaRecords as $m) {
                TutorialMedia::create([
                    'tutorial_id' => $experience->id,
                    'user_id' => $user->id,
                    'type' => $m['type'],
                    'path' => $m['path'],
                ]);
            }

            foreach ($data['urls'] as $url) {
                TutorialOutsideLink::create([
                    'tutorial_id' => $experience->id,
                    'url' => $url,
                ]);
            }
        }
    }
}