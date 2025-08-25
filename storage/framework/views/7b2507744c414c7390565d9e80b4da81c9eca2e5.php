<div class="subcategories-grid">
    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($category->hasChildren()): ?>
            <a href="<?php echo e(route('categories.subcategories', [$type, $category->id])); ?>" 
               class="subcategory-card" style="text-decoration: none; color: inherit;">
                <div class="subcategory-icon">
                    <i class="fas fa-folder"></i>
                </div>
                <h3 class="subcategory-title"><?php echo e($category->name); ?></h3>
                <p style="color: #666; font-size: 0.9rem; margin-top: 0.5rem;">يحتوي على المزيد</p>
            </a>
        <?php else: ?>
            <?php if($type == 'files'): ?>
                <a href="<?php echo e(route('category.files', $category->id)); ?>" 
                   class="subcategory-card" style="text-decoration: none; color: inherit;">
                    <div class="subcategory-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3 class="subcategory-title"><?php echo e($category->name); ?></h3>
                    <p style="color: #28a745; font-size: 0.9rem; margin-top: 0.5rem; font-weight: bold;">جاهز للاستخدام</p>
                </a>
            <?php elseif($type == 'lessons'): ?>
                <a href="<?php echo e(route('category.lessons', $category->id)); ?>" 
                   class="subcategory-card" style="text-decoration: none; color: inherit;">
                    <div class="subcategory-icon">
                        <i class="fas fa-play-circle"></i>
                    </div>
                    <h3 class="subcategory-title"><?php echo e($category->name); ?></h3>
                    <p style="color: #27ae60; font-size: 0.9rem; margin-top: 0.5rem; font-weight: bold;">جاهز للمشاهدة</p>
                </a>
            <?php else: ?>
                 <a href="<?php echo e(route('category.exams', $category->id)); ?>" 
                   class="subcategory-card" style="text-decoration: none; color: inherit;">
                    <div class="subcategory-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3 class="subcategory-title"><?php echo e($category->name); ?></h3>
                    <p style="color: #28a745; font-size: 0.9rem; margin-top: 0.5rem; font-weight: bold;">جاهز للاستخدام</p>
                </a>
            <?php endif; ?>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div><?php /**PATH C:\xampp\htdocs\platform\resources\views/includes/subcategories-grid.blade.php ENDPATH**/ ?>