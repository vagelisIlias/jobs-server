<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use App\Models\JobPost;
use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);

        $users = User::factory(10)->create();
        JobPost::factory(10)->recycle($users)->create();

        $category = Category::factory()->create();
        Category::factory(10)->recycle($category)->create();
    }
}
