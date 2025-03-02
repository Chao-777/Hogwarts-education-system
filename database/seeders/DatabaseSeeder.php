<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::truncate();

        User::factory()->count(7)->teacher()->create();
        User::factory()->count(70)->student()->create();

        $this->call(CourseSeeder::class);
        $this->call(AssessmentsSeeder::class);
    }
}
