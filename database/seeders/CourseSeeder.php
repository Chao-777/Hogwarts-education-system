<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Course::truncate();

        // Create 5 Harry Potter courses
        $courses = [
            Course::create(['course_code' => 'HP101', 'course_name' => 'Defence Against the Dark Arts']),
            Course::create(['course_code' => 'HP102', 'course_name' => 'Potions']),
            Course::create(['course_code' => 'HP103', 'course_name' => 'Herbology']),
            Course::create(['course_code' => 'HP104', 'course_name' => 'Transfiguration']),
            Course::create(['course_code' => 'HP105', 'course_name' => 'Care of Magical Creatures']),
        ];

        // Randomly assign all users 
        $allUserIds = range(0, 77);  
        foreach ($allUserIds as $userId) {
            $user = User::find($userId);

            if ($user) {
                // Select random courses for each user (3-4 courses per user)
                $randomCourses = $this->getRandomCourses($courses);

                foreach ($randomCourses as $course) {
                    // Enroll the user in the course (teacher or student role is determined by user->is_teacher)
                    $course->users()->attach($user->id);
                }
            }
        }
    }

    /**
     * Get random courses for a user.
     *
     * @param array $courses
     * @return array
     */
    private function getRandomCourses(array $courses)
    {
        // Shuffle the courses array randomly
        shuffle($courses);

        // Pick 3-4 random courses from the shuffled array
        $numberOfCourses = rand(3, 4);

        // Return the subset of courses
        return array_slice($courses, 0, $numberOfCourses);
    }
}