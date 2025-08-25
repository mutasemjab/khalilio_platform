

<?php $__env->startSection('content'); ?>
<div class="subcategories" style="display: block;">
    <?php if(isset($backRoute) && $backRoute == 'dashboard'): ?>
        <?php echo $__env->make('includes.back-button', ['route' => 'dashboard', 'text' => 'رجوع للرئيسية'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php else: ?>
        <?php echo $__env->make('includes.back-button', ['route' => 'categories.show', 'params' => [$type], 'text' => 'رجوع'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
    
    <?php echo $__env->make('includes.section-title', ['title' => $categoryTitle], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <?php if($categories->count() > 0): ?>
        <?php echo $__env->make('includes.subcategories-grid', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php else: ?>
        <?php echo $__env->make('includes.empty-state', ['message' => 'لا توجد عناصر فرعية', 'description' => 'هذه الفئة فارغة حالياً'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\platform\resources\views/sections/subcategories.blade.php ENDPATH**/ ?>