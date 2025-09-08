<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use App\Models\JobPost;
use Illuminate\Database\Seeder;

class JobPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JobPost::factory(10)->create();
    }
}
