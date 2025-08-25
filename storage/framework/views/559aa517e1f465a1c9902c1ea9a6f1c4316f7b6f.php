<div class="exams-grid">
    <?php $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="exam-card">
            <?php if($exam->has_active_attempt): ?>
                <div class="active-attempt" title="لديك محاولة نشطة"></div>
            <?php endif; ?>
            
            <div class="exam-card-header">
                <h3 class="exam-title"><?php echo e($exam->name); ?></h3>
                <div class="exam-status-badge status-<?php echo e($exam->status); ?>">
                    <?php if($exam->status == 'available'): ?>
                        <?php if($exam->has_active_attempt): ?>
                            <i class="fas fa-play-circle"></i> قيد التنفيذ
                        <?php else: ?>
                            <i class="fas fa-check-circle"></i> متاح للحل
                        <?php endif; ?>
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
                    <?php echo e(Str::limit($exam->description, 120)); ?>

                </div>
            <?php endif; ?>

            <div class="exam-stats">
                <div class="stat-item">
                    <div class="stat-icon questions">
                        <i class="fas fa-list"></i>
                    </div>
                    <div class="stat-value"><?php echo e($exam->questions->count()); ?></div>
                    <div class="stat-label">سؤال</div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-icon duration">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-value"><?php echo e($exam->duration_minutes); ?></div>
                    <div class="stat-label">دقيقة</div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-icon attempts">
                        <i class="fas fa-redo"></i>
                    </div>
                    <div class="stat-value"><?php echo e($exam->user_attempts_count); ?> من <?php echo e($exam->max_attempts); ?></div>
                    <div class="stat-label">محاولات</div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-icon grade">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-value"><?php echo e(number_format($exam->total_grade, 0)); ?></div>
                    <div class="stat-label">نقطة</div>
                </div>
            </div>

            <div class="exam-progress">
                <div class="progress-info">
                    <span class="attempts-info">
                        <?php if($exam->has_active_attempt): ?>
                            محاولة نشطة - اكملها الآن
                        <?php elseif($exam->user_attempts_count > 0): ?>
                            المحاولات المستخدمة: <?php echo e($exam->user_attempts_count); ?> من <?php echo e($exam->max_attempts); ?>

                        <?php else: ?>
                            لم تجرب هذا الامتحان بعد
                        <?php endif; ?>
                    </span>
                    <?php if($exam->best_score !== null): ?>
                        <span class="best-score">أفضل درجة: <?php echo e(number_format($exam->best_score, 1)); ?>/<?php echo e(number_format($exam->total_grade, 0)); ?></span>
                    <?php endif; ?>
                </div>
                <div class="progress-bar">
                    <?php
                        $progressPercentage = 0;
                        if ($exam->max_attempts > 0) {
                            $progressPercentage = ($exam->user_attempts_count / $exam->max_attempts) * 100;
                        }
                    ?>
                    <div class="progress-fill" style="width: <?php echo e(min(100, $progressPercentage)); ?>%"></div>
                </div>
            </div>

            <div class="exam-actions">
                <?php if($exam->has_active_attempt): ?>
                    <a href="<?php echo e(route('exam.take', ['examId' => $exam->id, 'attemptId' => $exam->getUserLastAttempt(session('user_id'))->id])); ?>" class="btn-exam btn-continue">
                        <i class="fas fa-arrow-left"></i>
                        متابعة الامتحان
                    </a>
                <?php elseif($exam->can_attempt && $exam->status == 'available'): ?>
                    <a href="<?php echo e(route('exam.show', $exam->id)); ?>" class="btn-exam btn-primary">
                        <i class="fas fa-play"></i>
                        بدء الامتحان
                    </a>
                <?php else: ?>
                    <button class="btn-exam btn-disabled" disabled>
                        <i class="fas fa-lock"></i>
                        <?php if($exam->status != 'available'): ?>
                            الامتحان غير متاح
                        <?php else: ?>
                            استنفدت المحاولات
                        <?php endif; ?>
                    </button>
                <?php endif; ?>
                
                <a href="<?php echo e(route('exam.show', $exam->id)); ?>" class="btn-exam btn-secondary">
                    <i class="fas fa-info-circle"></i>
                    التفاصيل
                </a>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div><?php /**PATH C:\xampp\htdocs\platform\resources\views/includes/exams-grid.blade.php ENDPATH**/ ?>