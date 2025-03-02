<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Assessment;
use App\Models\Course;

class AssessmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Assessment::truncate();

        $courseIds = [1, 2, 3, 4, 5]; 

        foreach ($courseIds as $courseId) {
            // Create 1st assessment for the course
            Assessment::create([
                'assessment_title' => 'Week 1 Peer Review',
                'instruction' => 'Provide feedback on your peer’s first assignment submission.',
                'required_review' => 2,
                'max_score' => 100,
                'due_date' => now()->addDays(7), 
                'time' => '23:59:00',
                'type' => 'student-select', 
                'course_id' => $courseId, 
            ]);

            // Create 2nd assessment for the course
            Assessment::create([
                'assessment_title' => 'Week 2 Peer Review',
                'instruction' => 'Evaluate the second draft of the peer’s assignment.',
                'required_review' => 3,
                'max_score' => 100,
                'due_date' => now()->addDays(14), 
                'time' => '23:59:00',
                'type' => 'teacher-assign', 
                'course_id' => $courseId,
            ]);

            // Additional 3 assessments

            // Create 3rd assessment for the course
            Assessment::create([
                'assessment_title' => 'Week 3 Peer Review',
                'instruction' => 'Assess the improvements made on your peer’s project based on the previous feedback.',
                'required_review' => 2,
                'max_score' => 100,
                'due_date' => now()->addDays(21), 
                'time' => '23:59:00',
                'type' => 'student-select', 
                'course_id' => $courseId,
            ]);

            // Create 4th assessment for the course
            Assessment::create([
                'assessment_title' => 'Week 4 Peer Review',
                'instruction' => 'Provide feedback on your peer’s presentation slides for the upcoming group project.',
                'required_review' => 3,
                'max_score' => 100,
                'due_date' => now()->addDays(28), 
                'time' => '23:59:00',
                'type' => 'teacher-assign', 
                'course_id' => $courseId,
            ]);

            // Create 5th assessment for the course
            Assessment::create([
                'assessment_title' => 'Final Peer Review',
                'instruction' => 'Evaluate the final draft of your peer’s capstone project.',
                'required_review' => 2,
                'max_score' => 100,
                'due_date' => now()->addDays(35), 
                'time' => '23:59:00',
                'type' => 'student-select', 
                'course_id' => $courseId,
            ]);
        }
    }
}