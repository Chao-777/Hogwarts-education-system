<?php $__env->startSection('title', 'Assessment Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <?php if(auth()->check() && !auth()->user()->is_teacher): ?>
        <!-- Student View: Assessment Details -->
        <div class="card mt-4">
            <div class="card-body">
                <h2 class="card-title"><?php echo e($assessment->assessment_title); ?></h2>
                <p><strong>Due Date:</strong> <?php echo e($assessment->due_date->format('d M Y H:i')); ?></p>
                <p><strong>Instructions:</strong> <?php echo e($assessment->instruction); ?></p>
                <p><strong>Points:</strong> <?php echo e($assessment->max_score); ?></p>
                <p><strong>Assessment Type:</strong> <?php echo e(ucfirst(str_replace('-', ' ', $assessment->type))); ?></p>
            </div>
        </div>

        <!-- Received Reviews -->
        <div class="card mt-4">
            <div class="card-header">
                <h3 class="card-title">Reviews You Have Received</h3>
            </div>
            <div class="card-body">
                <?php if($receivedReviews->isEmpty()): ?>
                    <p class="text-muted">You haven't received any reviews yet.</p>
                <?php else: ?>
                    <ul class="list-group">
                        <?php $__currentLoopData = $receivedReviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="list-group-item">
                                <strong>Reviewer:</strong> <?php echo e($review->reviewer->name); ?> <br>
                                <strong>Rating:</strong> <?php echo e($review->rating); ?> / 5 <br>
                                <strong>Review:</strong> <?php echo e($review->review_text); ?>

                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>

        <!-- Submit Peer Review -->
        <div class="card mt-4">
            <div class="card-header">
                <h3 class="card-title">Submit Peer Review</h3>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('reviews.store', $assessment->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label for="reviewee_id" class="form-label">Select Reviewee</label>
                        <select name="reviewee_id" id="reviewee_id" class="form-select" required>
                            <option value="">-- Select a student to review --</option>
                            <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($student->id !== auth()->id()): ?> <!-- Prevent self-selection -->
                                    <option value="<?php echo e($student->id); ?>"><?php echo e($student->name); ?></option>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="review_text" class="form-label">Review</label>
                        <textarea name="review_text" id="review_text" class="form-control" rows="4" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="rating" class="form-label">Rating (1 to 5)</label>
                        <select name="rating" id="rating" class="form-select" required>
                            <option value="1">1 - Not Good</option>
                            <option value="2">2 - Fair</option>
                            <option value="3">3 - Good</option>
                            <option value="4">4 - Very Good</option>
                            <option value="5">5 - Excellent</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit Review</button>
                </form>
            </div>
        </div>

    <?php elseif(auth()->check() && auth()->user()->is_teacher && $assessment->course->teachers->contains(auth()->user())): ?>
        <!-- Teacher View: Assessment Details -->
        <div class="card mt-4">
            <div class="card-body">
                <h2 class="card-title"><?php echo e($assessment->assessment_title); ?></h2>
                <p><strong>Due Date:</strong> <?php echo e($assessment->due_date->format('d M Y H:i')); ?></p>
                <p><strong>Instructions:</strong> <?php echo e($assessment->instruction); ?></p>
                <p><strong>Points:</strong> <?php echo e($assessment->max_score); ?></p>
                <p><strong>Assessment Type:</strong> <?php echo e(ucfirst(str_replace('-', ' ', $assessment->type))); ?></p>
            </div>
        </div>

        <!-- List of Students in the Course -->
        <div class="card mt-4">
            <div class="card-header">
                <h3 class="card-title">Students in this Course</h3>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5><?php echo e($student->name); ?></h5>
                                    <p><strong>Reviews Submitted:</strong> <?php echo e($student->submitted_reviews_count); ?></p>
                                    <p><strong>Reviews Received:</strong> <?php echo e($student->received_reviews_count); ?></p>
                                    <p><strong>Score:</strong> 
                                        <?php if($student->assessmentScores->isNotEmpty()): ?>
                                            <?php echo e($student->assessmentScores->first()->score); ?>

                                        <?php else: ?>
                                            <span class="text-warning">Not assigned</span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                                <div class="col-md-6 text-end">
                                    <a href="<?php echo e(route('teacher.student-reviews', ['assessment' => $assessment->id, 'student' => $student->id])); ?>" class="btn btn-primary">View Reviews & Assign Score</a>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>

    <?php else: ?>
        <!-- Unauthorized Access -->
        <div class="alert alert-danger mt-4">
            You are not authorized to view this page.
        </div>
    <?php endif; ?>

</div>

<?php if(session('error')): ?>
    <div class="alert alert-danger">
        <?php echo e(session('error')); ?>

    </div>
<?php endif; ?>

<?php if(session('success')): ?>
    <div class="alert alert-success">
        <?php echo e(session('success')); ?>

    </div>
<?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/WebAppDev/Assign2/Myapp/resources/views/assessments/show.blade.php ENDPATH**/ ?>