


<?php $__env->startSection('content'); ?>
<div class="exam-details-section" style="display: block;">
    <a href="javascript:history.back()" class="back-btn" style="display: block;">
        <i class="fas fa-arrow-right"></i>
        رجوع
    </a>

    <div class="exam-details-container">
        <div class="exam-header">
            <h1 class="exam-title"><?php echo e($exam->name); ?></h1>
            <div class="exam-status-badge status-<?php echo e($exam->status); ?>">
                <?php if($exam->status == 'available'): ?>
                    <i class="fas fa-check-circle"></i> متاح للحل
                <?php elseif($exam->status == 'upcoming'): ?>
                    <i class="fas fa-clock"></i> قريباً
                <?php elseif($exam->status == 'expired'): ?>
                    <i class="fas fa-times-circle"></i> منتهي الصلاحية
                <?php else: ?>
                    <i class="fas fa-pause-circle"></i> غير نشط
                <?php endif; ?>
            </div>
        </div>

        <?php if($exam->description): ?>
            <div class="exam-description">
                <?php echo e($exam->description); ?>

            </div>
        <?php endif; ?>

        <div class="exam-info-grid">
            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="info-content">
                    <h3>المدة الزمنية</h3>
                    <p><?php echo e($exam->formatted_duration); ?></p>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-list"></i>
                </div>
                <div class="info-content">
                    <h3>عدد الأسئلة</h3>
                    <p><?php echo e($exam->questions->count()); ?> سؤال</p>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="info-content">
                    <h3>الدرجة الكاملة</h3>
                    <p><?php echo e($exam->total_grade); ?> نقطة</p>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="info-content">
                    <h3>درجة النجاح</h3>
                    <p><?php echo e($exam->pass_grade); ?> نقطة</p>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-redo"></i>
                </div>
                <div class="info-content">
                    <h3>المحاولات المتاحة</h3>
                    <p><?php echo e($exam->max_attempts - $userAttempts); ?> من <?php echo e($exam->max_attempts); ?></p>
                </div>
            </div>

            <?php if($bestScore !== null): ?>
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-medal"></i>
                    </div>
                    <div class="info-content">
                        <h3>أفضل درجة</h3>
                        <p><?php echo e($bestScore); ?> / <?php echo e($exam->total_grade); ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <?php if($exam->instructions): ?>
            <div class="exam-instructions">
                <h3><i class="fas fa-info-circle"></i> تعليمات الامتحان</h3>
                <div class="instructions-content">
                    <?php if(is_array($exam->instructions)): ?>
                        <ul>
                            <?php $__currentLoopData = $exam->instructions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $instruction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($instruction); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    <?php else: ?>
                        <?php echo e($exam->instructions); ?>

                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="exam-actions">
            <?php if($exam->can_attempt): ?>
                <form action="<?php echo e(route('exam.start', $exam->id)); ?>" method="POST" class="start-exam-form">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn-start-exam">
                        <i class="fas fa-play"></i>
                        بدء الامتحان
                    </button>
                </form>
            <?php else: ?>
                <?php if($exam->status != 'available'): ?>
                    <button class="btn-disabled" disabled>
                        <i class="fas fa-lock"></i>
                        الامتحان غير متاح
                    </button>
                <?php else: ?>
                    <button class="btn-disabled" disabled>
                        <i class="fas fa-times"></i>
                        استنفدت المحاولات المتاحة
                    </button>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <?php if($userAttempts > 0): ?>
            <div class="previous-attempts">
                <h3><i class="fas fa-history"></i> المحاولات السابقة</h3>
                <!-- You can add a table of previous attempts here -->
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\platform\resources\views/sections/exam-details.blade.php ENDPATH**/ ?>