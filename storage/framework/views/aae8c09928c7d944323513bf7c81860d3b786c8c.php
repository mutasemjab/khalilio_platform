


<?php $__env->startSection('content'); ?>
<div class="exams-section" style="display: block;">
    <?php if(isset($backRoute) && $backRoute == 'dashboard'): ?>
        <?php echo $__env->make('includes.back-button', ['route' => 'dashboard', 'text' => 'رجوع للرئيسية'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php elseif(isset($backRoute) && $backRoute == 'categories.subcategories'): ?>
        <?php echo $__env->make('includes.back-button', ['route' => 'categories.subcategories', 'params' => $backParams, 'text' => 'رجوع'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php else: ?>
        <?php echo $__env->make('includes.back-button', ['route' => 'categories.show', 'params' => ['exams'], 'text' => 'رجوع'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

    <?php echo $__env->make('includes.section-title', ['title' => $categoryTitle . ' - الامتحانات'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?>
    
    <?php if($exams->count() > 0): ?>
        <?php echo $__env->make('includes.exams-grid', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php else: ?>
        <?php echo $__env->make('includes.empty-state', ['message' => 'لا توجد امتحانات', 'description' => 'هذه الفئة لا تحتوي على امتحانات حالياً'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\platform\resources\views/sections/exams.blade.php ENDPATH**/ ?>