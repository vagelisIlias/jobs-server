<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobPost>
 */
class JobPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence(3);
        return [
            'user_id' => User::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $this->faker->paragraph(3),
            'requirements' => $this->faker->sentence(10),
            'location' => $this->faker->city,
            'department' => $this->faker->word,
            'employment_type' => $this->faker->randomElement(['full-time', 'part-time', 'contract']),
            'status' => $this->faker->randomElement(['open', 'closed', 'draft']),
        ];
    }
}
