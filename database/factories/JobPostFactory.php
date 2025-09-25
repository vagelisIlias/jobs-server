<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
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
            'category_id' => Category::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $this->faker->paragraph(3),
            'requirements' => $this->faker->sentence(10),
            'salary' => '£' . $this->faker->numberBetween(30, 120),
            'location' => $this->faker->city,
            'department' => ucfirst($this->faker->word),
            'employment_type' => $this->faker->randomElement(['full-time', 'part-time', 'contract']),
            'experience_level' => $this->faker->randomElement(['entry', 'junior', 'junior-mid', 'mid', 'mid-senior', 'senior', 'lead', 'mentor']),
            'status' => $this->faker->randomElement(['open', 'closed', 'draft']),
        ];
    }
}
