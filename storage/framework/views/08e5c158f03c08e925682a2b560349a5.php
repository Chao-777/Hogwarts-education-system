<?php $__env->startSection('title', 'Top Reviewers'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <h3 class="mb-4">Top 5 Reviewers</h3>
    
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="thead-light">
                <tr>
                    <th>Reviewer</th>
                    <th>Average Rating</th>
                    <th>Total Reviews Given</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $topReviewers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reviewer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($reviewer->name); ?></td>
                        <td><?php echo e(number_format($reviewer->average_rating, 2)); ?> / 5</td>
                        <td><?php echo e($reviewer->reviews_given_count); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    
    <?php if($topReviewers->isEmpty()): ?>
        <div class="alert alert-warning mt-4">
            No reviewers available at the moment.
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/WebAppDev/Assign2/Myapp/resources/views/top-reviewers.blade.php ENDPATH**/ ?>