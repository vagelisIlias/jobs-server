<?php

namespace Database\Seeders;

use App\Models\JobPost;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(JobPostSeeder::class);
        User::factory(10)->create();
    }
}
