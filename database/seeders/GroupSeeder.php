<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Assessment;
use App\Models\Course;
use App\Models\User;
use App\Models\AssessmentScore;

class GroupSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function assignGroups($assessmentId)
    {
        $assessment = Assessment::findOrFail($assessmentId);
    
        // Only assign groups if the assessment type is 'teacher-assign'
        if ($assessment->type === 'teacher-assign') {
            $students = $assessment->course->students->shuffle();  
    
            $groupSize = 5;
            $groupCounter = 1;
    
            //random assign students and autocrease the group name
            foreach ($students->chunk($groupSize) as $groupStudents) {
                foreach ($groupStudents as $student) {
                    AssessmentScore::updateOrCreate(
                        [
                            'assessment_id' => $assessmentId,
                            'user_id' => $student->id,
                        ],
                        [
                            'group_name' => 'Group ' . $groupCounter  // Assign group name
                        ]
                    );
                }
                $groupCounter++;
            }
    
            return redirect()->back()->with('success', 'Groups assigned successfully for teacher-assign assessment.');
        }
    
        return redirect()->back()->with('error', 'This assessment does not require group assignment.');
    }
}