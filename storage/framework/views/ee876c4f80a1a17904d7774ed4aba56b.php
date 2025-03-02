<?php $__env->startSection('title', 'Group Lists'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h2>Group Lists for <?php echo e($assessment->assessment_title); ?></h2>

    <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="card mt-4">
            <div class="card-header">
                <h4><?php echo e($group->group_name); ?></h4>
            </div>
            <div class="card-body">
                <?php if(isset($groupMembers[$group->group_name]) && $groupMembers[$group->group_name]->isNotEmpty()): ?>
                    <ul class="list-group">
                        <?php $__currentLoopData = $groupMembers[$group->group_name]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="list-group-item">
                                <?php echo e($member->user->name); ?>

                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted">No members in this group yet.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<!-- Script to toggle visibility of group members -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Add event listeners to all group toggles
        document.querySelectorAll('.group-toggle').forEach(function (toggle) {
            toggle.addEventListener('click', function () {
                const groupName = this.getAttribute('data-group');
                const membersList = document.getElementById('members-' + groupName);

                // Toggle visibility
                if (membersList.style.display === 'none') {
                    membersList.style.display = 'block';
                } else {
                    membersList.style.display = 'none';
                }
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/WebAppDev/Assign2/Myapp/resources/views/groups.blade.php ENDPATH**/ ?>