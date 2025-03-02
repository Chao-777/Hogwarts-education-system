<?php $__env->startSection('title'); ?>
Home
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="container">
    <!-- Display success message -->
    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <h3>Your Courses</h3>

    <?php if(auth()->guard()->check()): ?>
        <?php if($courses->isEmpty()): ?>
            <p class="text-muted">You are not enrolled in or teaching any courses.</p>
        <?php else: ?>
            <ul class="list-group">
                <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="list-group-item">
                        <a href="<?php echo e(route('courses.show', $course->id)); ?>">
                            <strong><?php echo e($course->course_code); ?></strong> - <?php echo e($course->course_name); ?>

                        </a>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        <?php endif; ?>
    <?php else: ?>
        <p class="text-muted">You need to be logged in to view your courses.</p>
    <?php endif; ?>
</div>

    <!--display the link for uploading -->
<div class="container">

    <!-- Display the upload link only if the user is logged in and is a teacher -->
    <?php if(auth()->check() && auth()->user()->is_teacher): ?>
        <div class="mt-4">
            <a href="<?php echo e(route('home.uploadForm')); ?>" class="btn btn-primary">Create New Course</a>
        </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/WebAppDev/Assign2/Myapp/resources/views/home.blade.php ENDPATH**/ ?>