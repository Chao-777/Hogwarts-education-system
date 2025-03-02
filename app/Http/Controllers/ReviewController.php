<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // Method for submitting peer reviews
    public function store(Request $request, $assessmentId)
    {
        // Validate only the reviewee and review text during submission
        $request->validate([
            'reviewee_id' => 'required|exists:users,id',
            'review_text' => 'required|string|min:20', // Require at least 20 words
        ]);

        // Check if the student has already reviewed the reviewee for this assessment
        $alreadyReviewed = Review::where('assessment_id', $assessmentId)
            ->where('student_id', auth()->id())
            ->where('reviewee_id', $request->reviewee_id)
            ->exists();

        if ($alreadyReviewed) {
            return redirect()->back()->withErrors('You have already reviewed this student.');
        }

        // Store the review (no rating at this point, as it's added by the reviewee later)
        Review::create([
            'student_id' => auth()->id(),
            'reviewee_id' => $request->reviewee_id,
            'assessment_id' => $assessmentId,
            'review_text' => $request->review_text,
        ]);

        return redirect()->route('student.assessment.show', $assessmentId)->with('success', 'Review submitted successfully.');
    }

    // Method for reviewees to rate the reviews they've received
    public function rateReview(Request $request, $reviewId)
    {
        // Validate the incoming rating
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // Find the review
        $review = Review::findOrFail($reviewId);

        // Ensure only the reviewee can rate the review
        if (auth()->id() !== $review->reviewee_id) {
            return redirect()->back()->with('error', 'You are not authorized to rate this review.');
        }

        // Update the review's rating
        $review->update([
            'rating' => $request->input('rating'),
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'You have successfully rated the review.');
    }

    // Method to get the top reviewers based on average rating
    public function topReviewers()
    {
        // Retrieve the top 5 reviewers based on average rating
        $topReviewers = User::whereHas('reviewsGiven', function ($query) {
                $query->whereNotNull('rating'); // Only consider reviews that have been rated
            })
            ->withCount('reviewsGiven') // Count the number of reviews they've given
            ->withAvg('reviewsGiven as average_rating', 'rating') // Get the average rating for their reviews
            ->groupBy('users.id') // Group by the user ID
            ->having('reviews_given_count', '>=', 1) // Ensure they've given at least one review
            ->orderBy('average_rating', 'desc') // Sort by average rating
            ->take(5) // Get the top 5 reviewers
            ->get();

        return view('top-reviewers', compact('topReviewers'));
    }
}