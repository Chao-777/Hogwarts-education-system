<?php $__env->startSection('title', 'Student Reviews'); ?>

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
    <div class="card mt-4">
        <div class="card-body">
            <h2 class="card-title mb-4">Reviews for <?php echo e($student->name); ?></h2>

            <!-- Display submitted reviews -->
            <div class="mb-4">
                <h3 class="card-subtitle mb-3">Submitted Reviews</h3>
                <?php if($submittedReviews->isEmpty()): ?>
                    <p class="text-muted">No reviews submitted by this student yet.</p>
                <?php else: ?>
                    <ul class="list-group">
                        <?php $__currentLoopData = $submittedReviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="list-group-item">
                                <strong>Reviewee:</strong> <?php echo e($review->reviewee->name); ?><br>
                                <strong>Review:</strong> <?php echo e($review->review_text); ?><br>
                                <strong>Rating:</strong> <?php echo e($review->rating); ?> / 5
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                <?php endif; ?>
            </div>

            <!-- Display received reviews -->
            <div class="mb-4">
                <h3 class="card-subtitle mb-3">Received Reviews</h3>
                <?php if($receivedReviews->isEmpty()): ?>
                    <p class="text-muted">This student has not received any reviews yet.</p>
                <?php else: ?>
                    <ul class="list-group">
                        <?php $__currentLoopData = $receivedReviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="list-group-item">
                                <strong>Reviewer:</strong> <?php echo e($review->reviewer->name ?? 'Anonymous'); ?><br>
                                <strong>Review:</strong> <?php echo e($review->review_text); ?><br>
                                <strong>Rating:</strong> <?php echo e($review->rating); ?> / 5
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                <?php endif; ?>
            </div>

            <!-- Display assigned score -->
            <div class="mb-4">
                <h3 class="card-subtitle mb-3">Assigned Score</h3>
                <p class="lead">
                    <strong>Score:</strong> 
                    <?php
                        $score = $student->assessmentScores->firstWhere('assessment_id', $assessment->id);
                    ?>

                    <?php if($score): ?>
                        <?php echo e($score->score); ?> / <?php echo e($assessment->max_score); ?>

                    <?php else: ?>
                        <span class="text-warning">No score assigned yet.</span>
                    <?php endif; ?>
                </p>
            </div>

            <!-- Form to assign or update the score -->
            <div class="mb-4">
                <h3 class="card-subtitle mb-3">Assign or Update Score</h3>
                <form action="<?php echo e(route('teacher.assign-score', ['assessment' => $assessment->id, 'student' => $student->id])); ?>" method="POST" class="form-inline">
                    <?php echo csrf_field(); ?>
                    <div class="input-group">
                        <input type="number" name="score" class="form-control" placeholder="Enter Score" 
                               min="0" max="<?php echo e($assessment->max_score); ?>" 
                               value="<?php echo e(old('score', $student->assessmentScores->first()->score ?? 0)); ?>" required>
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-success">Submit Score</button>
                        </div>
                    </div>
                    <small class="form-text text-muted">Score should be between 0 and <?php echo e($assessment->max_score); ?>.</small>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/WebAppDev/Assign2/Myapp/resources/views/assessments/student-reviews.blade.php ENDPATH**/ ?>