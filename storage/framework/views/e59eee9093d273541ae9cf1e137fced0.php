<?php $__env->startSection('title', 'Teacher Assessment Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">

    <!-- Display Assessment Details -->
    <div class="card mt-4">
        <div class="card-body">
            <h2 class="card-title"><?php echo e($assessment->assessment_title); ?></h2>
            <p><strong>Due Date:</strong> <?php echo e($assessment->due_date->format('d M Y H:i')); ?></p>
            <p><strong>Instructions:</strong> <?php echo e($assessment->instruction); ?></p>
            <p><strong>Points:</strong> <?php echo e($assessment->max_score); ?></p>
            <p><strong>Assessment Type:</strong> <?php echo e(ucfirst(str_replace('-', ' ', $assessment->type))); ?></p>
        </div>
    </div>

    <!-- Students List and Scores -->
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Students in this Course</h3>
            <span class="text-muted"><?php echo e($students->total()); ?> Students</span>
        </div>
        <?php if($assessment->type === 'teacher-assign' && auth()->user()->is_teacher): ?>
        <div class="text-right mb-4">
            <form action="<?php echo e(route('assessments.assign-groups', $assessment->id)); ?>" method="GET">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-primary"> Assign Groups</button>
                <a href="<?php echo e(route('assessments.groups', $assessment->id)); ?>" class="btn btn-secondary">
            Group Lists
                </a>
            </form>
        </div>
        <?php endif; ?>

                <!-- Group Lists Button -->



        <div class="card-body">
            <?php if($students->isEmpty()): ?>
                <p class="text-muted">No students enrolled in this course yet.</p>
            <?php else: ?>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Reviews Submitted</th>
                            <th>Reviews Received</th>
                            <th>Score</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($student->name); ?></td>
                                <td><?php echo e($student->submitted_reviews_count); ?></td>
                                <td><?php echo e($student->received_reviews_count); ?></td>
                                <td>
                                    <?php if($student->assessmentScores->isNotEmpty()): ?>
                                        <?php echo e($student->assessmentScores->first()->score); ?>

                                    <?php else: ?>
                                        <span class="text-warning">Not assigned</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <a href="<?php echo e(route('teacher.student-reviews', ['assessment' => $assessment->id, 'student' => $student->id])); ?>" class="btn btn-primary">
                                        View & Score
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>

                <!-- Pagination Links -->
                <div class="mt-4 d-flex justify-content-center">
                    <?php echo e($students->links('pagination::bootstrap-4')); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/WebAppDev/Assign2/Myapp/resources/views/assessments/teacher-assessment.blade.php ENDPATH**/ ?>