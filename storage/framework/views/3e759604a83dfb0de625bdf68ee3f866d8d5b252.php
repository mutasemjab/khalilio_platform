<div class="multiple-choice-options">
    <?php if($currentQuestion->options): ?>
        <?php $__currentLoopData = $currentQuestion->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <label class="option-label">
                <input type="radio" name="answer" value="<?php echo e($option); ?>" class="option-radio">
                <div class="option-content">
                    <div class="option-indicator"><?php echo e(chr(65 + $index)); ?></div>
                    <div class="option-text"><?php echo e($option); ?></div>
                </div>
            </label>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
</div><?php /**PATH C:\xampp\htdocs\platform\resources\views/includes/question-types/multiple-choice.blade.php ENDPATH**/ ?>