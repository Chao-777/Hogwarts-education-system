<?php $__env->startSection('title', 'Student Assessment Details'); ?>

<?php $__env->startSection('content'); ?>

<?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <ul>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>


<div class="container">
    <!-- Assessment Details -->
    <div class="card mt-4">
        <div class="card-body">
            <h2 class="card-title"><?php echo e($assessment->assessment_title); ?></h2>
            <p><strong>Due Date:</strong> <?php echo e($assessment->due_date->format('d M Y H:i')); ?></p>
            <p><strong>Instructions:</strong> <?php echo e($assessment->instruction); ?></p>
            <p><strong>Points:</strong> <?php echo e($assessment->max_score); ?></p>
            <p><strong>Required Reviews:</strong> <?php echo e($assessment->required_review); ?></p>
            <p><strong>Submitted Reviews:</strong> <?php echo e($submittedReviewsCount); ?> / <?php echo e($assessment->required_review); ?></p>
            <p><strong>Assessment Type:</strong> <?php echo e(ucfirst(str_replace('-', ' ', $assessment->type))); ?></p>
        </div>
    </div>


    <!-- Reviews You Have Received Section -->
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Reviews You Have Received</h3>
            <a href="<?php echo e(route('reviewers.top')); ?>" class="btn btn-info">Top Reviewers</a>
        </div>
        <div class="card-body">
            <?php if($receivedReviews->isEmpty()): ?>
                <p class="text-muted">You haven't received any reviews yet.</p>
            <?php else: ?>
                <ul class="list-group">
                    <?php $__currentLoopData = $receivedReviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="list-group-item">
                            <strong>Reviewer:</strong> <?php echo e($review->reviewer->name); ?> <br>
                            <strong>Review:</strong> <?php echo e($review->review_text); ?> <br>
                            <strong>Rating:</strong>
                            <?php if($review->rating !== null): ?>
                                <!-- If already rated, show the rating -->
                                <span class="text-success"><?php echo e($review->rating); ?> / 5</span> (You rated this review)
                            <?php else: ?>
                                <!-- If not rated, allow the reviewee to rate the review -->
                                <form action="<?php echo e(route('reviews.rate', $review->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <label for="rating_<?php echo e($review->id); ?>">Rate this peer review (1-5):</label>
                                    <select name="rating" id="rating_<?php echo e($review->id); ?>" class="form-select" required>
                                        <option value="1">1 - Poor </option>
                                        <option value="2">2 - Below Average</option>
                                        <option value="3">3 - Average </option>
                                        <option value="4">4 - Good </option>
                                        <option value="5">5 - Excellent</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary mt-2">Submit Rating</button>
                                </form>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

    <!-- Submitted Reviews Section -->
    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title">Your Submitted Reviews</h3>
        </div>
        <div class="card-body">
            <?php if($submittedReviews->isEmpty()): ?>
                <p class="text-muted">You haven't submitted any reviews yet.</p>
            <?php else: ?>
                <ul class="list-group">
                    <?php $__currentLoopData = $submittedReviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="list-group-item">
                            <strong>Reviewee:</strong> <?php echo e($review->reviewee->name); ?> <br>
                            <strong>Review:</strong> <?php echo e($review->review_text); ?> <br>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            <?php endif; ?>
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
        <form action="<?php echo e(route('reviews.store', $assessment->id)); ?>" method="POST" id="peerReviewForm">
            <?php echo csrf_field(); ?>
            <!-- Reviewee Selection -->
            <div class="mb-3">
                <label for="reviewee_id" class="form-label">Select Reviewee</label>
                <select name="reviewee_id" id="reviewee_id" class="form-select" >
                    <option value="">-- Select a student to review --</option>
                    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($student->id !== auth()->id() && !$submittedReviewees->contains($student->id)): ?> <!-- Prevent self-selection and already reviewed students -->
                            <option value="<?php echo e($student->id); ?>"><?php echo e($student->name); ?></option>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/WebAppDev/Assign2/Myapp/resources/views/assessments/student-assessment.blade.php ENDPATH**/ ?>