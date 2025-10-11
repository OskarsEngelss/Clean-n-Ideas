<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Experience>
 */
class ExperienceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->catchPhrase();
        $categories = ['Housekeeping', 'Furniture', 'Clothing & Textiles', 'Electronics', 'Vehicles', 'Miscellaneous', 'Outdoors & Garden', 'Personal Care Items'];
        $category = $this->faker->randomElement($categories);

        return [
            'title' => $title,
            'category' => $category,
            'description' => 'Clothes',
            'tutorial' => fake()->paragraph(3, true),
            'slug' => Str::slug($title) . '-' . uniqid(),
            'thumbnail' => 'images/defaults/' . mb_strtolower($category, 'UTF-8') . '-default-thumbnail.webp',
        ];
    }
}
