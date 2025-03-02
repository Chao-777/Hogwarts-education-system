<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $courses = auth()->user()->courses;
        return view('home', compact('courses'));
    }

    public function uploadForm()
    {
        // Ensure only teachers can access this page
        if (!auth()->check() || !auth()->user()->is_teacher) {
            return redirect()->route('home')->with('error', 'You are not authorized to access this page.');
        }
    
        return view('upload');  // Note: We're moving this view to the main 'views' folder
    }

// Upload the files
    public function uploadCourseFile(Request $request)
    {
        try {
            $request->validate([
                'course_file' => 'required|mimes:txt|max:2048'
            ]);

            // Store the uploaded file
            $filePath = $request->file('course_file')->store('uploads');

            // Parse the file and process the content
            $fileContent = Storage::get($filePath);

            // Call the function to process the content
            $this->processCourseFile($fileContent);

            return redirect()->back()->with('success', 'Course file uploaded and processed successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        } catch (\Exception $e) {
            // Handle general errors and display the error message
            return redirect()->back()->with('error', 'An error occurred while processing the file: ' . $e->getMessage());
        }
    }

// Process data and save to the database
    private function processCourseFile($content)
    {
        $lines = explode(PHP_EOL, $content);
        $courseInfo = [];
        $teachers = [];
        $assessments = [];
        $students = [];
        
        $parsingSection = null;

        foreach ($lines as $line) {
            $line = trim($line);

            if (empty($line)) {
                continue;
            }

            if (str_starts_with($line, 'Course:')) {
                $courseInfo['name'] = trim(str_replace('Course:', '', $line));
            } elseif (str_starts_with($line, 'Code:')) {
                $courseInfo['code'] = trim(str_replace('Code:', '', $line));
            } elseif (str_starts_with($line, 'Teachers:')) {
                $parsingSection = 'teachers';
            } elseif (str_starts_with($line, 'Assessments:')) {
                $parsingSection = 'assessments';
            } elseif (str_starts_with($line, 'Students:')) {
                $parsingSection = 'students';
            } else {
                switch ($parsingSection) {
                    case 'teachers':
                        $teachers[] = $line;
                        break;
                    case 'assessments':
                        // Updated to handle the required_review, time, and instruction fields
                        list($title, $maxScore, $dueDate, $time, $type, $requiredReview, $instruction) = explode(',', $line);
                        $assessments[] = [
                            'title' => trim($title),
                            'max_score' => trim($maxScore),
                            'due_date' => trim($dueDate),
                            'time' => trim($time), // time added
                            'type' => trim($type),
                            'required_review' => trim($requiredReview), // required_review added
                            'instruction' => trim($instruction), // instruction added
                        ];
                        break;
                    case 'students':
                        $students[] = $line;
                        break;
                }
            }
        }

        // Check if the course already exists
        $existingCourse = Course::where('course_code', $courseInfo['code'])->first();
        if ($existingCourse) {
            throw new \Exception('Course with the code "' . $courseInfo['code'] . '" already exists.');
        }

        // Create new course
        DB::transaction(function () use ($courseInfo, $teachers, $assessments, $students) {
            $course = Course::create([
                'course_name' => $courseInfo['name'],
                'course_code' => $courseInfo['code']
            ]);

            // Attach teachers to course
            foreach ($teachers as $teacherEmail) {
                $teacher = User::firstOrCreate(
                    ['email' => $teacherEmail],
                    ['name' => 'Teacher', 'is_teacher' => true]
                );
                $course->teachers()->attach($teacher->id);
            }

            // Create assessments
            foreach ($assessments as $assessmentData) {
                $course->assessments()->create([
                    'assessment_title' => $assessmentData['title'],
                    'max_score' => $assessmentData['max_score'],
                    'due_date' => $assessmentData['due_date'],
                    'time' => $assessmentData['time'], // Insert time
                    'type' => $assessmentData['type'],
                    'required_review' => $assessmentData['required_review'], // Insert required_review
                    'instruction' => $assessmentData['instruction'], // Insert instruction
                ]);
            }

            // Enroll students in course (skip if they are already enrolled)
            foreach ($students as $studentEmail) {
                $student = User::firstOrCreate(
                    ['email' => $studentEmail],
                    ['name' => 'Student', 'is_teacher' => false]
                );
                if (!$course->students()->where('user_id', $student->id)->exists()) {
                    $course->students()->attach($student->id);
                }
            }
        });
    }
}