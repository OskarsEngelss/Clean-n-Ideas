<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Experience;
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
        User::factory()->admin()->create();

        $basicUser = User::factory()->basicUser()->create();
        Experience::factory(30)->create([
            'user_id' => $basicUser->id,
        ]);

        $users = User::factory(20)->create();
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
