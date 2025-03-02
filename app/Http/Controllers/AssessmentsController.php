<?php
namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentScore;
use Illuminate\Support\Facades\Validator;
use App\Models\Course;
use App\Models\User;
use App\Models\Review;
use Illuminate\Http\Request;

class AssessmentsController extends Controller
{
    public function show($id)
    {
        // Find the assessment by ID
        $assessment = Assessment::findOrFail($id);

        $userId = auth()->id();

        // Get the course related to the assessment
        $course = $assessment->course;

        // Get all students enrolled in the course (excluding the current student)
        $students = $course->users()->where('is_teacher', false)->get();

        $receivedReviews = Review::where('assessment_id', $id)
        ->where('reviewee_id', $userId)
        ->get();

        // Return the assessment details view with the assessment and students
        return view('assessments.show', compact('assessment', 'students', 'receivedReviews'));
    }

    public function store(Request $request, $courseId)
    {
        // Validate the incoming request data to prevent empty submission.
        $request->validate([
            'assessment_title' => 'required|string|max:20',
            'instruction' => 'required|string',
            'required_review' => 'required|integer|min:1',
            'max_score' => 'required|integer|min:1|max:100',
            'due_date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'type' => 'required|in:student-select,teacher-assign',
        ]);

        // Find the course by ID
        $course = Course::findOrFail($courseId);

        // Create a new assessment for the course
        $course->assessments()->create([
            'assessment_title' => $request->assessment_title,
            'instruction' => $request->instruction,
            'required_review' => $request->required_review,
            'max_score' => $request->max_score,
            'due_date' => $request->due_date,
            'time' => $request->time,
            'type' => $request->type,
        ]);

        // Redirect back with success message
        return redirect()->route('courses.show', $course->id)->with('success', 'Assessment created successfully.');
    }


    //update the assessment
    public function update(Request $request, $id)
    {
        // Find the assessment
        $assessment = Assessment::findOrFail($id);

        // Check if there are any reviews already submitted for this assessment
        $reviewsExist = $assessment->reviews()->exists();

        if ($reviewsExist) {
            return redirect()->back()->withErrors('This assessment cannot be updated because reviews have already been submitted.');
        }


        // Validate the request data
        $request->validate([
            'assessment_title' => 'required|string|max:20',
            'instruction' => 'required|string',
            'required_review' => 'required|integer|min:1',
            'max_score' => 'required|integer|min:1|max:100',
            'due_date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'type' => 'required|in:student-select,teacher-assign',
        ]);

        // Combine due date and time into a single datetime
        $dueDateTime = $request->due_date . ' ' . $request->due_time;

        // Update the assessment with new values
        $assessment->update([
            'assessment_title' => $request->assessment_title,
            'instruction' => $request->instruction,
            'required_review' => $request->required_review,
            'max_score' => $request->max_score,
            'due_date' => $request->due_date,
            'time' => $request->time,
            'type' => $request->type,
        ]);

        // Redirect back to the course details page with a success message
        return redirect()->route('courses.show', $assessment->course_id)->with('success', 'Assessment updated successfully.');
    }

    public function submitReview(Request $request, $assessmentId)
    {
        $errors = [];
    
        // Custom error messages
        $messages = [
            'review_text.required' => 'You need to provide a review before submitting.',
            'review_text.min' => 'Your review must be at least 20 words long.',
            'reviewee_id.required' => 'You need to select a student to review.',
            'reviewee_id.exists' => 'The selected student does not exist in the system.',
        ];
    
        // Validate the review submission
        $request->validate([
            'review_text' => 'required|string|min:20', // Ensure the input is at least 20 characters
            'reviewee_id' => 'required|exists:users,id',
        ], $messages);
    
        // Check if the student already submitted the required number of reviews
        $submittedReviewsCount = Review::where('student_id', auth()->id())
            ->where('assessment_id', $assessmentId)
            ->count();
    
        // Retrieve the assessment
        $assessment = Assessment::findOrFail($assessmentId);
    
        // Ensure the student doesn't exceed the number of required reviews
        if ($submittedReviewsCount >= $assessment->required_review) {
            $errors[] = 'You have already submitted the required number of reviews.';
        }
    
        // Ensure the student doesn't review the same person more than once
        $existingReview = Review::where('student_id', auth()->id())
            ->where('assessment_id', $assessmentId)
            ->where('reviewee_id', $request->reviewee_id)
            ->exists();
    
        if ($existingReview) {
            $errors[] = 'You have already submitted a review for this student.';
        }
    
        // If there are errors, redirect back with the errors array
        if (!empty($errors)) {
            return redirect()->back()->withErrors($errors);
        }
    
        // Create the new review if no errors
        Review::create([
            'review_text' => $request->review_text,
            'student_id' => auth()->id(),
            'reviewee_id' => $request->reviewee_id,
            'assessment_id' => $assessmentId,
        ]);
    
        return redirect()->back()->with('success', 'Review submitted successfully.');
    }

    //show students's assessments
    public function showStudentAssessment($assessmentId)
    {
        // Find the assessment by ID
        $assessment = Assessment::findOrFail($assessmentId);
    
        // Get the current authenticated user ID
        $userId = auth()->id();
    
        // Count the number of reviews submitted by this student for this assessment
        $submittedReviewsCount = Review::where('student_id', $userId)
            ->where('assessment_id', $assessmentId)
            ->count();
    
        // Get the reviews this student has submitted for this assessment
        $submittedReviews = Review::where('student_id', $userId)
            ->where('assessment_id', $assessmentId)
            ->with('reviewee') // assuming the review has a relationship with the user being reviewed
            ->get();
    
        // Get the reviews the student has received
        $receivedReviews = Review::where('reviewee_id', $userId)
            ->where('assessment_id', $assessmentId)
            ->with('reviewer') // assuming the review has a relationship with the reviewer
            ->get();
    
        // Get all the students for the assessment (for the select reviewee dropdown)
        $students = $assessment->course->users()
            ->where('is_teacher', false)
            ->get();
    
        // Get students the current user has already reviewed
        $submittedReviewees = Review::where('student_id', $userId)
            ->where('assessment_id', $assessmentId)
            ->pluck('reviewee_id');
    
        // Pass the data to the view
        return view('assessments.student-assessment', compact(
            'assessment',
            'submittedReviews',
            'submittedReviewsCount',
            'receivedReviews',
            'students',
            'submittedReviewees'
        ));
    }


    public function showTeacherAssessment($assessmentId)
    {
        // Find the assessment by ID
        $assessment = Assessment::findOrFail($assessmentId);

        // Ensure the user is a teacher
        if (!auth()->user()->is_teacher || !$assessment->course->teachers->contains(auth()->user())) {
            abort(403, 'You are not authorized to access this page.');
        }

        // Get all students in the course and their review counts
        $students = $assessment->course->users()
            ->where('is_teacher', false)
            ->withCount([
                'submittedReviews' => function ($query) use ($assessmentId) {
                    $query->where('assessment_id', $assessmentId);
                },
                'receivedReviews' => function ($query) use ($assessmentId) {
                    $query->where('assessment_id', $assessmentId);
                }
            ])
            ->with(['assessmentScores' => function ($query) use ($assessmentId) {
                $query->where('assessment_id', $assessmentId);
            }])
            ->paginate();

        // Return the teacher assessment view
        return view('assessments.teacher-assessment', compact('assessment', 'students'));
    }
    
    public function studentReviews($assessmentId, $studentId)
    {
        $assessment = Assessment::findOrFail($assessmentId);
        $student = User::findOrFail($studentId);
    
        // Get all submitted and received reviews for this student
        $submittedReviews = Review::where('assessment_id', $assessmentId)
            ->where('student_id', $studentId)
            ->get();
    
        $receivedReviews = Review::where('assessment_id', $assessmentId)
            ->where('reviewee_id', $studentId)
            ->get();
    
        return view('assessments.student-reviews', compact('assessment', 'student', 'submittedReviews', 'receivedReviews'));
    }
    
    public function assignScore(Request $request, $assessmentId, $studentId)
    {
        // Custom error messages
        $messages = [
            'score.required' => 'You need to input a score before submitting.',
            'score.integer' => 'The score must be a valid integer.',
            'score.min' => 'The score must be at least 0.',
            'score.max' => 'The score must not exceed 100.',
        ];
    
        // Validate the score input with custom error messages
        $validatedData = $request->validate([
            'score' => 'required|integer|min:0|max:100',
        ], $messages);
    
        // Update or create the score for this student and assessment
        AssessmentScore::updateOrCreate(
            ['assessment_id' => $assessmentId, 'user_id' => $studentId],
            ['score' => $validatedData['score']]
        );
    
        return redirect()->back()->with('success', 'Score updated successfully!');
    }

    public function assignGroups($assessmentId)
    {
        // Retrieve the assessment
        $assessment = Assessment::findOrFail($assessmentId);
    
        // Get all students enrolled in the course related to this assessment
        $students = $assessment->course->users()->where('is_teacher', false)->get();
    
        // Define the number of students per group (adjust as needed)
        $studentsPerGroup = 5;
    
        // Shuffle the students to randomize
        $shuffledStudents = $students->shuffle();
    
        // Create group names and assign students
        $groupCounter = 1;
        $groupName = "Group " . $groupCounter;
        $groupedStudents = [];
    
        foreach ($shuffledStudents as $index => $student) {
            if ($index % $studentsPerGroup == 0 && $index != 0) {
                $groupCounter++;
                $groupName = "Group " . $groupCounter;
            }
    
            // Create or update an entry in the assessment_scores table for each student
            AssessmentScore::updateOrCreate(
                ['assessment_id' => $assessmentId, 'user_id' => $student->id],
                ['group_name' => $groupName]
            );
    
            // Add students to the group
            $groupedStudents[$groupName][] = $student->name;
        }
    
        // Redirect to the group page with success message
        return redirect()->route('assessments.assign-groups', $assessmentId)->with('success', 'Groups assigned successfully!');
    }


    public function showGroups($assessmentId)
    {
        // Get the assessment
        $assessment = Assessment::findOrFail($assessmentId);
    
        // Fetch all the distinct groups for this assessment
        $groups = AssessmentScore::where('assessment_id', $assessmentId)
            ->select('group_name')
            ->distinct()
            ->get();
    
        // Fetch group members
        $groupMembers = AssessmentScore::where('assessment_id', $assessmentId)
            ->with('user') // Load the user (group member)
            ->get()
            ->groupBy('group_name');
    
        return view('groups', compact('assessment', 'groups', 'groupMembers'));
    }

    
}