<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Experience;
use App\Models\TutorialList;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::factory()->admin()->create();
        TutorialList::create([
            'user_id' => $admin->id,
            'name' => 'Favourites',
            'is_favourite' => true,
            'is_public' => false,
        ]);

        $basicUser = User::factory()->basicUser()->create();
        TutorialList::create([
            'user_id' => $basicUser->id,
            'name' => 'Favourites',
            'is_favourite' => true,
            'is_public' => false,
        ]);

        Experience::factory(30)->create([
            'user_id' => $basicUser->id,
        ]);

        $users = User::factory(20)->create()->each(function ($user) {
            TutorialList::create([
                'user_id' => $user->id,
                'name' => 'Favourites',
                'is_favourite' => true,
                'is_public' => false,
            ]);
        });

        foreach ($users as $user) {
            DB::table('follows')->insert([
                'follower_id' => $basicUser->id,
                'followed_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->call([
            ExperienceSeeder::class,
        ]);
    }
}
