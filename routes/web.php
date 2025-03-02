<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AssessmentsController;
use App\Http\Controllers\ReviewController;

Route::post('/reviews/store/{assessment}', [ReviewController::class, 'store'])->name('reviews.store');

Route::get('/', function () {
    return view('/home');
});

// Home Route
Route::get('/home', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('home');

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Courses Route
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');

//Enrollment
Route::post('/courses/{id}/enroll', [CourseController::class, 'enrollStudent'])->name('courses.enroll');

//Add assessment
Route::post('/courses/{course}/assessments', [AssessmentsController::class, 'store'])->name('assessments.store');

//update assessment
Route::put('/assessments/{id}', [AssessmentsController::class, 'update'])->name('assessments.update');

// Routes for students
Route::get('/assessments/{assessment}/student', [AssessmentsController::class, 'showStudentAssessment'])->name('student.assessment.show');

// Routes for teachers
Route::get('/assessments/{assessment}/teacher', [AssessmentsController::class, 'showTeacherAssessment'])->name('teacher.assessment.show');

//Assessment Submit
Route::post('assessments/{assessment}/submit', [AssessmentsController::class, 'submitReview'])->name('assessments.submitReview');

//review submit
Route::post('/reviews/store/{assessment}', [ReviewController::class, 'store'])->name('reviews.store');

//list of reviews
Route::get('/assessments/{assessment}/student-reviews/{student}', [AssessmentsController::class, 'studentReviews'])
    ->name('teacher.student-reviews');

// assign scores
Route::post('/assessments/{assessment}/assign-score/{student}', [AssessmentsController::class, 'assignScore'])
    ->name('teacher.assign-score');

// Show upload form 
Route::get('/upload', [HomeController::class, 'uploadForm'])->name('home.uploadForm');

// Insert data to database 
Route::post('/upload', [HomeController::class, 'uploadCourseFile'])->name('home.upload');

// Route for rating a review
Route::post('/reviews/{review}/rate', [ReviewController::class, 'rateReview'])->name('reviews.rate');

// Route for viewing the top reviewers
Route::get('/top-reviewers', [ReviewController::class, 'topReviewers'])->name('reviewers.top');


// Route to assign groups for the assessment
Route::get('/assessments/{assessment}/assign-groups', [AssessmentsController::class, 'assignGroups'])->name('assessments.assign-groups');

// Route to show the groups
Route::get('/assessments/{assessment}/groups', [AssessmentsController::class, 'showGroups'])->name('assessments.groups');

// Default Login and Auth routes
require __DIR__.'/auth.php';

