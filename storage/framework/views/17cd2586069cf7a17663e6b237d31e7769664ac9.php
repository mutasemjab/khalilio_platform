


<?php $__env->startSection('content'); ?>
<div class="exam-result-section" style="display: block;">
    <div class="result-container">
        <!-- Result Header -->
        <div class="result-header">
            <div class="result-icon <?php echo e($attempt->isPassed() ? 'success' : 'failure'); ?>">
                <?php if($attempt->isPassed()): ?>
                    <i class="fas fa-trophy"></i>
                <?php else: ?>
                    <i class="fas fa-times-circle"></i>
                <?php endif; ?>
            </div>
            
            <h1 class="result-title">
                <?php if($attempt->isPassed()): ?>
                    مبروك! لقد نجحت في الامتحان
                <?php else: ?>
                    للأسف، لم تحقق درجة النجاح
                <?php endif; ?>
            </h1>
            
            <div class="exam-name"><?php echo e($attempt->exam->name); ?></div>
        </div>

        <!-- Score Summary -->
        <div class="score-summary">
            <div class="score-circle">
                <div class="score-progress" data-percentage="<?php echo e($attempt->percentage); ?>">
                    <div class="score-value">
                        <span class="percentage"><?php echo e(number_format($attempt->percentage, 1)); ?>%</span>
                        <span class="fraction"><?php echo e($attempt->score); ?> / <?php echo e($attempt->exam->total_grade); ?></span>
                    </div>
                </div>
            </div>
            
            <div class="score-details">
                <div class="score-item">
                    <span class="label">درجتك:</span>
                    <span class="value"><?php echo e($attempt->score); ?> نقطة</span>
                </div>
                <div class="score-item">
                    <span class="label">النسبة المئوية:</span>
                    <span class="value"><?php echo e(number_format($attempt->percentage, 1)); ?>%</span>
                </div>
                <div class="score-item">
                    <span class="label">درجة النجاح:</span>
                    <span class="value"><?php echo e($attempt->exam->pass_grade); ?> نقطة</span>
                </div>
                <div class="score-item">
                    <span class="label">الحالة:</span>
                    <span class="value <?php echo e($attempt->isPassed() ? 'passed' : 'failed'); ?>">
                        <?php echo e($attempt->isPassed() ? 'ناجح' : 'راسب'); ?>

                    </span>
                </div>
            </div>
        </div>

        <!-- Exam Statistics -->
        <div class="exam-statistics">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-list"></i>
                </div>
                <div class="stat-content">
                    <h3>إجمالي الأسئلة</h3>
                    <p><?php echo e($attempt->questionAnswers->count()); ?> سؤال</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon correct">
                    <i class="fas fa-check"></i>
                </div>
                <div class="stat-content">
                    <h3>إجابات صحيحة</h3>
                    <p><?php echo e($attempt->questionAnswers->where('is_correct', true)->count()); ?> سؤال</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon incorrect">
                    <i class="fas fa-times"></i>
                </div>
                <div class="stat-content">
                    <h3>إجابات خاطئة</h3>
                    <p><?php echo e($attempt->questionAnswers->where('is_correct', false)->count()); ?> سؤال</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3>وقت الإنجاز</h3>
                    <p>
                        <?php if($attempt->submitted_at): ?>
                            <?php echo e($attempt->started_at->diffInMinutes($attempt->submitted_at)); ?> دقيقة
                        <?php else: ?>
                            <?php echo e($attempt->exam->duration_minutes); ?> دقيقة (انتهى الوقت)
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>

       
        <?php if($attempt->exam->show_results_immediately): ?>
            <!-- Detailed Results -->
            <div class="detailed-results">
                <h2><i class="fas fa-list-ul"></i> تفاصيل الإجابات</h2>
                
                <div class="questions-review">
                    <?php $__currentLoopData = $attempt->questionAnswers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $questionAnswer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="question-review <?php echo e($questionAnswer->is_correct ? 'correct' : ($questionAnswer->is_correct === false ? 'incorrect' : 'ungraded')); ?>">
                            <div class="question-header">
                                <div class="question-number">
                                    السؤال <?php echo e($index + 1); ?>

                                    <?php if($questionAnswer->is_correct): ?>
                                        <i class="fas fa-check-circle correct"></i>
                                    <?php elseif($questionAnswer->is_correct === false): ?>
                                        <i class="fas fa-times-circle incorrect"></i>
                                    <?php else: ?>
                                        <i class="fas fa-question-circle pending"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="question-grade">
                                    <?php echo e($questionAnswer->awarded_grade); ?> / <?php echo e($questionAnswer->question->grade); ?> نقطة
                                </div>
                            </div>

                            <div class="question-content">
                                <p class="question-text"><?php echo e($questionAnswer->question->question_text); ?></p>
                                
                                <?php if($questionAnswer->question->type == 'multiple_choice'): ?>
                                    <div class="user-answer">
                                        <strong>إجابتك:</strong> 
                                        <?php if($questionAnswer->user_answer !== null && $questionAnswer->user_answer !== ''): ?>
                                            <?php echo e($questionAnswer->user_answer); ?>

                                        <?php else: ?>
                                            <span class="no-answer">لم تجب على هذا السؤال</span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if($questionAnswer->question->correct_answers && is_array($questionAnswer->question->correct_answers)): ?>
                                        <div class="correct-answer">
                                            <strong>الإجابة الصحيحة:</strong>
                                            <?php $__currentLoopData = $questionAnswer->question->correct_answers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $correctIndex): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if(is_array($questionAnswer->question->options) && isset($questionAnswer->question->options[$correctIndex])): ?>
                                                    <?php echo e($questionAnswer->question->options[$correctIndex]); ?>

                                                    <?php if(!$loop->last): ?>, <?php endif; ?>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                <?php elseif($questionAnswer->question->type == 'true_false'): ?>
                                    <div class="user-answer">
                                        <strong>إجابتك:</strong> 
                                        <?php if($questionAnswer->user_answer !== null && $questionAnswer->user_answer !== ''): ?>
                                            <?php echo e($questionAnswer->user_answer == 'true' ? 'صحيح' : 'خطأ'); ?>

                                        <?php else: ?>
                                            <span class="no-answer">لم تجب على هذا السؤال</span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if($questionAnswer->question->correct_answers && is_array($questionAnswer->question->correct_answers)): ?>
                                        <div class="correct-answer">
                                            <strong>الإجابة الصحيحة:</strong>
                                            <?php echo e($questionAnswer->question->correct_answers[0] == 'true' ? 'صحيح' : 'خطأ'); ?>

                                        </div>
                                    <?php endif; ?>
                                    
                                <?php else: ?>
                                    <div class="user-answer">
                                        <strong>إجابتك:</strong> 
                                        <?php if($questionAnswer->user_answer): ?>
                                            <?php echo e($questionAnswer->user_answer); ?>

                                        <?php else: ?>
                                            <span class="no-answer">لم تجب على هذا السؤال</span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if($questionAnswer->question->correct_answers && is_array($questionAnswer->question->correct_answers)): ?>
                                        <div class="correct-answer">
                                            <strong>الإجابة الصحيحة:</strong>
                                            <?php $__currentLoopData = $questionAnswer->question->correct_answers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $correctAnswer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php echo e($correctAnswer); ?>

                                                <?php if(!$loop->last): ?>, <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php if($questionAnswer->question->explanation): ?>
                                    <div class="explanation">
                                        <strong>الشرح:</strong> <?php echo e($questionAnswer->question->explanation); ?>

                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Actions -->
        <div class="result-actions">
            <a href="<?php echo e(route('category.exams', $attempt->exam->category_exam_id)); ?>" class="btn-action btn-back">
                <i class="fas fa-arrow-right"></i>
                العودة للامتحانات
            </a>
            
            <?php if($attempt->exam->canUserAttempt(session('user_id'))): ?>
                <a href="<?php echo e(route('exam.show', $attempt->exam_id)); ?>" class="btn-action btn-retry">
                    <i class="fas fa-redo"></i>
                    إعادة المحاولة
                </a>
            <?php endif; ?>
            
            <button onclick="window.print()" class="btn-action btn-print">
                <i class="fas fa-print"></i>
                طباعة النتائج
            </button>
        </div>
    </div>
</div>

<style>
/* Print styles */
@media print {
    .result-actions,
    .back-btn,
    .animated-bg {
        display: none !important;
    }
    
    .exam-result-section {
        background: white !important;
        color: black !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate score circle
    const scoreCircle = document.querySelector('.score-progress');
    const percentage = scoreCircle.getAttribute('data-percentage');
    
    setTimeout(() => {
        scoreCircle.style.background = `conic-gradient(
            #27ae60 0deg ${percentage * 3.6}deg,
            #ecf0f1 ${percentage * 3.6}deg 360deg
        )`;
    }, 500);
    
    // Animate statistics cards
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 100 * index);
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\platform\resources\views/sections/exam-result.blade.php ENDPATH**/ ?>