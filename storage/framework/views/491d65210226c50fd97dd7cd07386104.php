<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Education Management System">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.122.0">
    <title><?php echo $__env->yieldContent('title'); ?> - Education Management System</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="app.css">

    <!-- Additional Styles -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }
        header {
            background-color: #343a40;
            color: white;
        }
        .header-title {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .btn-logout {
            font-size: 1rem;
        }
        .welcome-message {
            font-weight: bold;
        }
        .footer {
            margin-top: 4rem;
            padding: 1rem;
            background-color: #343a40;
            color: white;
            text-align: center;
        }
        .content-container {
            margin-top: 2rem;
        }
    </style>
</head>

<body>
<?php if(auth()->guard()->check()): ?>
    <!-- Header for Authenticated Users -->
    <header class="p-3">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <a href="<?php echo e(url('home')); ?>" class="text-white text-decoration-none">
                    <h2 class="welcome-message">
                        Welcome, <?php echo e(Auth::user()->name); ?> (<?php echo e(Auth::user()->is_teacher ? 'Teacher' : 'Student'); ?>)
                    </h2>
                </a>
                <form method="POST" action="<?php echo e(url('/logout')); ?>" class="d-inline">
                    <?php echo e(csrf_field()); ?>

                    <button type="submit" class="btn btn-danger btn-logout">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container content-container">
        <?php echo $__env->yieldContent('content'); ?>
    </div>

<?php else: ?>
    <!-- Header for Guest Users -->
    <header class="p-3">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <a href="<?php echo e(url('/')); ?>" class="text-white text-decoration-none">
                    <h2 class="header-title">Education Management System</h2>
                </a>
                <div class="text-end">
                    <a href="<?php echo e(route('login')); ?>" class="btn btn-outline-light me-2">Login</a>
                    <a href="<?php echo e(route('register')); ?>" class="btn btn-warning">Register</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content for Guests -->
    <div class="container content-container">
        <?php echo $__env->yieldContent('content'); ?>
        <div class="alert alert-info text-center fst-italic mt-4" role="alert">
            <strong>Please Login or Register to Continue</strong>
        </div>
    </div>
<?php endif; ?>

<?php echo $__env->make('layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html><?php /**PATH /var/www/html/WebAppDev/Assign2/Myapp/resources/views/layouts/master.blade.php ENDPATH**/ ?>