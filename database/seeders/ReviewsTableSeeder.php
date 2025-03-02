<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Assessment;
use Illuminate\Support\Facades\DB;

class ReviewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch 10 random users (ensure you have users and assessments available in your DB)
        $students = User::where('is_teacher', false)->inRandomOrder()->limit(10)->get();
        $assessments = Assessment::inRandomOrder()->limit(10)->get();

        // Ensure that we have both students and assessments in the DB
        if ($students->count() > 0 && $assessments->count() > 0) {
            foreach ($students as $index => $student) {
                // Ensure each student reviews another student (different from themselves)
                $reviewee = User::where('id', '!=', $student->id)->inRandomOrder()->first();

                // Insert the review record
                Review::create([
                    'student_id' => $student->id,
                    'reviewee_id' => $reviewee->id,
                    'assessment_id' => $assessments[$index % $assessments->count()]->id, // Rotate through assessments
                    'review_text' => 'This is a sample review for assessment ' . ($index + 1),
                    'rating' => rand(1, 5), // Random rating between 1 and 5
                ]);
            }
        } else {
            $this->command->error('Make sure you have at least 10 students and assessments in the database.');
        }
    }
}