@extends('layouts.master')

@section('title', 'Student Assessment Details')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


<div class="container">
    <!-- Assessment Details -->
    <div class="card mt-4">
        <div class="card-body">
            <h2 class="card-title">{{ $assessment->assessment_title }}</h2>
            <p><strong>Due Date:</strong> {{ $assessment->due_date->format('d M Y H:i') }}</p>
            <p><strong>Instructions:</strong> {{ $assessment->instruction }}</p>
            <p><strong>Points:</strong> {{ $assessment->max_score }}</p>
            <p><strong>Required Reviews:</strong> {{ $assessment->required_review }}</p>
            <p><strong>Submitted Reviews:</strong> {{ $submittedReviewsCount }} / {{ $assessment->required_review }}</p>
            <p><strong>Assessment Type:</strong> {{ ucfirst(str_replace('-', ' ', $assessment->type)) }}</p>
        </div>
    </div>


    <!-- Reviews You Have Received Section -->
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Reviews You Have Received</h3>
            <a href="{{ route('reviewers.top') }}" class="btn btn-info">Top Reviewers</a>
        </div>
        <div class="card-body">
            @if ($receivedReviews->isEmpty())
                <p class="text-muted">You haven't received any reviews yet.</p>
            @else
                <ul class="list-group">
                    @foreach ($receivedReviews as $review)
                        <li class="list-group-item">
                            <strong>Reviewer:</strong> {{ $review->reviewer->name }} <br>
                            <strong>Review:</strong> {{ $review->review_text }} <br>
                            <strong>Rating:</strong>
                            @if ($review->rating !== null)
                                <!-- If already rated, show the rating -->
                                <span class="text-success">{{ $review->rating }} / 5</span> (You rated this review)
                            @else
                                <!-- If not rated, allow the reviewee to rate the review -->
                                <form action="{{ route('reviews.rate', $review->id) }}" method="POST">
                                    @csrf
                                    <label for="rating_{{ $review->id }}">Rate this peer review (1-5):</label>
                                    <select name="rating" id="rating_{{ $review->id }}" class="form-select" required>
                                        <option value="1">1 - Poor </option>
                                        <option value="2">2 - Below Average</option>
                                        <option value="3">3 - Average </option>
                                        <option value="4">4 - Good </option>
                                        <option value="5">5 - Excellent</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary mt-2">Submit Rating</button>
                                </form>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <!-- Submitted Reviews Section -->
    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title">Your Submitted Reviews</h3>
        </div>
        <div class="card-body">
            @if ($submittedReviews->isEmpty())
                <p class="text-muted">You haven't submitted any reviews yet.</p>
            @else
                <ul class="list-group">
                    @foreach ($submittedReviews as $review)
                        <li class="list-group-item">
                            <strong>Reviewee:</strong> {{ $review->reviewee->name }} <br>
                            <strong>Review:</strong> {{ $review->review_text }} <br>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>


    <!-- Submit Reviews Section -->
    <div class="card mt-4">
    <div class="card-header">
        <h3 class="card-title">Submit Peer Review</h3>
    </div>


    <div class="card-body">
        <!-- Tips to encourage better reviews -->
        <div class="mb-4">
            <h5><strong>Tips for Writing a Better Review:</strong></h5>
            <p>To help your peer improve, consider the following tips:</p>
            <ul>
                <li><strong>Be specific:</strong> Provide clear examples of what was done well and what could be improved.</li>
                <li><strong>Use a respectful tone:</strong> Constructive criticism helps your peers grow.</li>
                <li><strong>Focus on solutions:</strong> Don't just point out problems—offer suggestions for improvement.</li>
            </ul>
        </div>

        <!-- Form for submitting the peer review -->
        <form action="{{ route('reviews.store', $assessment->id) }}" method="POST" id="peerReviewForm">
            @csrf
            <!-- Reviewee Selection -->
            <div class="mb-3">
                <label for="reviewee_id" class="form-label">Select Reviewee</label>
                <select name="reviewee_id" id="reviewee_id" class="form-select" >
                    <option value="">-- Select a student to review --</option>
                    @foreach ($students as $student)
                        @if($student->id !== auth()->id() && !$submittedReviewees->contains($student->id)) <!-- Prevent self-selection and already reviewed students -->
                            <option value="{{ $student->id }}">{{ $student->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <!-- Review Text -->
            <div class="mb-3">
                <label for="review_text" class="form-label">Review</label>
                <textarea name="review_text" id="review_text" class="form-control" rows="4" ></textarea>
                <small class="form-text text-muted" id="reviewFeedback">Your review must be at least 20 words.</small>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary" id="submitReviewBtn">Submit Review</button>
        </form>
    </div>
</div>


<!-- Script to evaluate the input text and give some suggestions -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const reviewText = document.getElementById('review_text');
    const reviewFeedback = document.getElementById('reviewFeedback');
    const submitBtn = document.getElementById('submitReviewBtn');

    // Function to analyse review content
    function analyzeReview() {
        const reviewContent = reviewText.value.trim();
        const wordCount = reviewContent.split(/\s+/).length; // Count words by splitting by spaces
        const minWordCount = 20; // Set minimum word count for a review
        const containsSpecificWords = /good|excellent|improve|detailed|specific/i.test(reviewContent); // Regex for detecting constructive terms

        // Provide real-time feedback based on the analysis
        if (wordCount < minWordCount) {
            reviewFeedback.innerHTML = `Your review is too short. It must be at least ${minWordCount} words.`;
            reviewFeedback.classList.add('text-danger');
            submitBtn.disabled = true;
        } else if (!containsSpecificWords) {
            reviewFeedback.innerHTML = `Your review lacks specificity. Try using words like "specific," "improve," or "detailed."`;
            reviewFeedback.classList.add('text-warning');
            submitBtn.disabled = false;
        } else {
            reviewFeedback.innerHTML = `Your review looks good! You can submit it now.`;
            reviewFeedback.classList.remove('text-danger', 'text-warning');
            reviewFeedback.classList.add('text-success');
            submitBtn.disabled = false;
        }
    }

    // Event listener for changes in the review text area
    reviewText.addEventListener('input', analyzeReview);

    // Initial validation on page load
    analyzeReview();
});
</script>





</div>
</div>
@endsection