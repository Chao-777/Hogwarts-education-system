<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    /**
     * Display the course details page.
     */
    public function show(Course $course)
    {
        // Get the teachers for the course
        $teachers = $course->users()->where('is_teacher', true)->get();

        //Select students that not enrolled this course
        $students = User::whereNotIn('id', function ($query) use ($course) {
            $query->select('user_id')
                  ->from('course_user')
                  ->where('course_id', $course->id);
        })->where('is_teacher', false)->get();

        // Return the course with teachers and assessments (assessments are accessed via $course->assessments in the view)
        return view('courses.show', compact('course', 'teachers', 'students'));
    }

    public function enrollStudent(Request $request, $id)
    {
        //error message to notify choosing student id.
        $messages = [
            'student_id.required' => 'You need to choose a student before enrolling.',
            'student_id.exists' => 'The selected student does not exist in the system.',
        ];

        // Validate that 'student_id' exists and is a valid user
        $request->validate([
            'student_id' => 'required|exists:users,id',
        ], $messages);
        
        // Find the course
        $course = Course::findOrFail($id);
    
        // Check if the student is already enrolled in the course
        $alreadyEnrolled = $course->users()->where('user_id', $request->student_id)->exists();
    


        if ($alreadyEnrolled) {
            return redirect()->back()->withErrors('This student is already enrolled in the course.');
        }
    
        // Enroll the student in the course
        $course->users()->attach($request->student_id);
        

        // Redirect with success message
        return redirect()->route('courses.show', $course->id)->with('success', 'Student enrolled successfully');
    }

}