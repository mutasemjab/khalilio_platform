<!-- Main Categories Grid -->
<div class="categories-grid">
    <?php if(isset($mainCategories) && count($mainCategories) > 0): ?>
        <?php $__currentLoopData = $mainCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('categories.show', $category['type'])); ?>" class="category-card" style="text-decoration: none; color: inherit;">
                <div class="category-icon <?php echo e($category['type']); ?>" style="color: <?php echo e($category['color']); ?>;">
                    <i class="<?php echo e($category['icon']); ?>"></i>
                </div>
                <h3 class="category-title"><?php echo e($category['name']); ?></h3>
                <p class="category-description"><?php echo e($category['description']); ?></p>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php else: ?>
        <div style="text-align: center; color: white; grid-column: 1/-1; padding: 3rem;">
            <i class="fas fa-exclamation-triangle" style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.5;"></i>
            <h3>لا توجد فئات متاحة</h3>
            <p>يرجى التواصل مع الإدارة لإضافة الفئات</p>
        </div>
    <?php endif; ?>
</div><?php /**PATH C:\xampp\htdocs\platform\resources\views/includes/main-categories.blade.php ENDPATH**/ ?>