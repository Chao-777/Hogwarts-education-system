<?php $__env->startSection('title', 'Course Details'); ?>

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
    <!-- Display the Course Name and Code -->
    <div class="card mt-4">
        <div class="card-body">
            <h2 class="card-title">Course: <?php echo e($course->course_name); ?> <small class="text-muted">(<?php echo e($course->course_code); ?>)</small></h2>
        </div>
    </div>

    <!-- Display Teachers -->
    <div class="card mt-4">
        <div class="card-header">
            <h3>Teachers</h3>
        </div>
        <div class="card-body">
            <?php if($teachers && $teachers->isEmpty()): ?>
                <p class="text-muted">No teachers are assigned to this course.</p>
            <?php else: ?>
                <ul class="list-group">
                    <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="list-group-item">
                            <strong><?php echo e($teacher->name); ?></strong> <span class="text-muted">(<?php echo e($teacher->email); ?>)</span>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

    <!-- JavaScript to show or hide the forms -->
    <script>
        function showEditForm(assessmentId) {
            document.getElementById('editAssessmentForm_' + assessmentId).style.display = 'block';
        }

        function hideEditForm(assessmentId) {
            document.getElementById('editAssessmentForm_' + assessmentId).style.display = 'none';
        }

        function showAddAssessmentForm() {
            document.getElementById('addAssessmentForm').style.display = 'block';
            document.getElementById('addAssessmentButton').style.display = 'none';
        }

        function hideAddAssessmentForm() {
            document.getElementById('addAssessmentForm').style.display = 'none';
            document.getElementById('addAssessmentButton').style.display = 'block';
        }
    </script>

 <!-- Display Assessments -->
<h3>Assessments</h3>
<?php if($course->assessments && !$course->assessments->isEmpty()): ?>
    <ul>
        <?php $__currentLoopData = $course->assessments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assessment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li>
                <!-- Check if the user is authenticated and is a teacher -->
                <?php if(auth()->check() && auth()->user()->is_teacher): ?>
                    <!-- Teacher-specific link -->
                    <a href="<?php echo e(route('teacher.assessment.show', $assessment->id)); ?>">
                        <?php echo e($assessment->assessment_title); ?> - 
                        Due: <?php echo e($assessment->due_date->format('d M Y')); ?> at <?php echo e($assessment->time); ?>

                    </a>

                    <!-- Display Edit Link for Teachers Only if Authenticated -->
                    <a href="#" id="editAssessmentLink_<?php echo e($assessment->id); ?>" class="ms-3 text-primary" onclick="event.preventDefault(); showEditForm(<?php echo e($assessment->id); ?>);">Edit</a>

                <?php elseif(auth()->check() && !auth()->user()->is_teacher): ?>
                    <!-- Student-specific link -->
                    <a href="<?php echo e(route('student.assessment.show', $assessment->id)); ?>">
                        <?php echo e($assessment->assessment_title); ?> - 
                        Due: <?php echo e($assessment->due_date->format('d M Y')); ?> at <?php echo e($assessment->time); ?>

                    </a>
                <?php endif; ?>

                <!-- Edit Form for Each Assessment (Hidden by Default) -->
                <?php if(auth()->check() && auth()->user()->is_teacher): ?>
                    <div id="editAssessmentForm_<?php echo e($assessment->id); ?>" class="card mt-3" style="display: none;">
                        <div class="card-header">
                            <h4>Edit Assessment: <?php echo e($assessment->assessment_title); ?></h4>
                        </div>
                        <div class="card-body">
                            <form action="<?php echo e(route('assessments.update', $assessment->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>

                                <!-- Assessment Title -->
                                <div class="mb-3">
                                    <label for="assessment_title_<?php echo e($assessment->id); ?>" class="form-label">Assessment Title (Max 20 characters)</label>
                                    <input type="text" name="assessment_title" id="assessment_title_<?php echo e($assessment->id); ?>" class="form-control" maxlength="20" value="<?php echo e(old('assessment_title', $assessment->assessment_title)); ?>" >
                                </div>

                                <!-- Instruction -->
                                <div class="mb-3">
                                    <label for="instruction_<?php echo e($assessment->id); ?>" class="form-label">Instruction</label>
                                    <textarea name="instruction" id="instruction_<?php echo e($assessment->id); ?>" class="form-control" rows="3" ><?php echo e(old('instruction', $assessment->instruction)); ?></textarea>
                                </div>

                                <!-- Number of Reviews Required -->
                                <div class="mb-3">
                                    <label for="required_review_<?php echo e($assessment->id); ?>" class="form-label">Number of Reviews Required</label>
                                    <input type="number" name="required_review" id="required_review_<?php echo e($assessment->id); ?>" class="form-control" min="1" value="<?php echo e(old('required_review', $assessment->required_review)); ?>" >
                                </div>

                                <!-- Maximum Score -->
                                <div class="mb-3">
                                    <label for="max_score_<?php echo e($assessment->id); ?>" class="form-label">Maximum Score (1 to 100)</label>
                                    <input type="number" name="max_score" id="max_score_<?php echo e($assessment->id); ?>" class="form-control" min="1" max="100" value="<?php echo e(old('max_score', $assessment->max_score)); ?>" >
                                </div>

                                <!-- Due Date -->
                                <div class="mb-3">
                                    <label for="due_date_<?php echo e($assessment->id); ?>" class="form-label">Due Date</label>
                                    <input type="date" name="due_date" id="due_date_<?php echo e($assessment->id); ?>" class="form-control" value="<?php echo e(old('due_date', $assessment->due_date->format('Y-m-d'))); ?>" >
                                </div>

                                <!-- Due Time -->
                                <div class="mb-3">
                                    <label for="time_<?php echo e($assessment->id); ?>" class="form-label">Due Time</label>
                                    <input type="time" name="time" id="time_<?php echo e($assessment->id); ?>" class="form-control" value="<?php echo e(old('time', $assessment->time)); ?>" >
                                </div>

                                <!-- Assessment Type -->
                                <div class="mb-3">
                                    <label for="type_<?php echo e($assessment->id); ?>" class="form-label">Assessment Type</label>
                                    <select name="type" id="type_<?php echo e($assessment->id); ?>" class="form-select" >
                                        <option value="student-select" <?php echo e(old('type', $assessment->type) === 'student-select' ? 'selected' : ''); ?>>Student Select</option>
                                        <option value="teacher-assign" <?php echo e(old('type', $assessment->type) === 'teacher-assign' ? 'selected' : ''); ?>>Teacher Assign</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-success">Update Assessment</button>
                                <button type="button" class="btn btn-secondary" onclick="hideEditForm(<?php echo e($assessment->id); ?>);">Cancel</button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
<?php endif; ?>
    </div>
    <?php if(auth()->check() && auth()->user()->is_teacher): ?>
        <!-- Button to show the Add Assessment Form -->
        <div class="mt-4">
            <button id="addAssessmentButton" class="btn btn-primary" onclick="showAddAssessmentForm()">Add New Assessment</button>
        </div>
        
        <!-- Form to add a peer review assessment -->
        <div id="addAssessmentForm" class="card mt-4" style="display: none;">
            <div class="card-header">
                <h3>Add Peer Review Assessment</h3>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('assessments.store', $course->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label for="assessment_title" class="form-label">Assessment Title (Max 20 characters)</label>
                        <input type="text" name="assessment_title" id="assessment_title" class="form-control"  >
                    </div>

                    <div class="mb-3">
                        <label for="instruction" class="form-label">Instruction</label>
                        <textarea name="instruction" id="instruction" class="form-control" rows="3" ></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="required_review" class="form-label">Number of Reviews Required</label>
                        <input type="number" name="required_review" id="required_review" class="form-control" min="1" value="1" >
                    </div>

                    <div class="mb-3">
                        <label for="max_score" class="form-label">Maximum Score (1 to 100)</label>
                        <input type="number" name="max_score" id="max_score" class="form-control" min="1" max="100" value="100" >
                    </div>

                    <div class="mb-3">
                        <label for="due_date" class="form-label">Due Date</label>
                        <input type="date" name="due_date" id="due_date" class="form-control" >
                    </div>

                    <div class="mb-3">
                        <label for="time" class="form-label">Due Time</label>
                        <input type="time" name="time" id="time" class="form-control" >
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label">Assessment Type</label>
                        <select name="type" id="type" class="form-select" >
                            <option value="student-select">Student Select</option>
                            <option value="teacher-assign">Teacher Assign</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Create Assessment</button>
                </form>
            </div>
        </div>

        <!-- Enrollment Form for Teachers -->
        <div class="card mt-4">
            <div class="card-header">
                <h3>Enroll a Student</h3>
            </div>
            
            <div class="card-body">
                <?php if($students->isEmpty()): ?>
                    <p class="text-muted">All students are already enrolled in this course.</p>
                <?php else: ?>
                    <form action="<?php echo e(route('courses.enroll', $course->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="student_id" class="form-label">Select a Student</label>
                            <select name="student_id" id="student_id" class="form-select" >
                                <option value="">-- Select a Student --</option>
                                <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($student->id); ?>"><?php echo e($student->name); ?> (<?php echo e($student->email); ?>)</option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Enroll Student</button>
                    </form>
                <?php endif; ?>
            </div>
        </div> 
    <?php endif; ?>
</div>



<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/WebAppDev/Assign2/Myapp/resources/views/courses/show.blade.php ENDPATH**/ ?>