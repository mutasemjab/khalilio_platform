


<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4><?php echo e(__('messages.exam_details')); ?>: <?php echo e($exam->name); ?></h4>
                    <div>
                        <a href="<?php echo e(route('exams.edit', $exam)); ?>" class="btn btn-warning btn-sm">
                            <?php echo e(__('messages.edit')); ?>

                        </a>
                        <a href="<?php echo e(route('questions.index', $exam)); ?>" class="btn btn-primary btn-sm">
                            <?php echo e(__('messages.manage_questions')); ?>

                        </a>
                        <a href="<?php echo e(route('exams.attempts', $exam)); ?>" class="btn btn-info btn-sm">
                            <?php echo e(__('messages.view_attempts')); ?>

                        </a>
                        <a href="<?php echo e(route('exams.index')); ?>" class="btn btn-secondary btn-sm">
                            <?php echo e(__('messages.back')); ?>

                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                    <?php endif; ?>

                    <div class="row">
                        <!-- Exam Information -->
                        <div class="col-md-8">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="25%"><?php echo e(__('messages.exam_name')); ?></th>
                                    <td><?php echo e($exam->name); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.description')); ?></th>
                                    <td><?php echo e($exam->description ?? __('messages.no_description')); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.category')); ?></th>
                                    <td><?php echo e($exam->category->name ?? __('messages.no_category')); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.duration')); ?></th>
                                    <td><?php echo e($exam->duration_minutes); ?> <?php echo e(__('messages.minutes')); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.start_time')); ?></th>
                                    <td><?php echo e($exam->start_time->format('Y-m-d H:i:s')); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.end_time')); ?></th>
                                    <td><?php echo e($exam->end_time->format('Y-m-d H:i:s')); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.total_grade')); ?></th>
                                    <td><?php echo e($exam->total_grade); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.pass_grade')); ?></th>
                                    <td><?php echo e($exam->pass_grade); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.max_attempts')); ?></th>
                                    <td><?php echo e($exam->max_attempts); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.status')); ?></th>
                                    <td>
                                        <?php if($exam->isAvailable()): ?>
                                            <span class="badge bg-success"><?php echo e(__('messages.active')); ?></span>
                                        <?php elseif($exam->start_time > now()): ?>
                                            <span class="badge bg-warning"><?php echo e(__('messages.upcoming')); ?></span>
                                        <?php elseif($exam->end_time < now()): ?>
                                            <span class="badge bg-secondary"><?php echo e(__('messages.expired')); ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-danger"><?php echo e(__('messages.inactive')); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- Exam Statistics -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6><?php echo e(__('messages.exam_statistics')); ?></h6>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-12 mb-3">
                                            <h4 class="text-primary"><?php echo e($exam->questions->count()); ?></h4>
                                            <small><?php echo e(__('messages.total_questions')); ?></small>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <h4 class="text-info"><?php echo e($exam->attempts->count()); ?></h4>
                                            <small><?php echo e(__('messages.total_attempts')); ?></small>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <h4 class="text-success"><?php echo e($exam->attempts->where('status', 'completed')->count()); ?></h4>
                                            <small><?php echo e(__('messages.completed_attempts')); ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php if($exam->instructions): ?>
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h6><?php echo e(__('messages.exam_instructions')); ?></h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="instructions-content">
                                            <?php if(is_array($exam->instructions)): ?>
                                                <?php $__currentLoopData = $exam->instructions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $instruction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <p><?php echo e($instruction); ?></p>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php else: ?>
                                                <?php echo nl2br(e($exam->instructions)); ?>

                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Questions Section -->
                    <div class="mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5><?php echo e(__('messages.exam_questions')); ?> (<?php echo e($exam->questions->count()); ?>)</h5>
                            <a href="<?php echo e(route('questions.create', $exam)); ?>" class="btn btn-success btn-sm">
                                <?php echo e(__('messages.add_question')); ?>

                            </a>
                        </div>

                        <?php if($exam->questions->count() > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th width="8%"><?php echo e(__('messages.order')); ?></th>
                                            <th width="40%"><?php echo e(__('messages.question')); ?></th>
                                            <th width="15%"><?php echo e(__('messages.type')); ?></th>
                                            <th width="10%"><?php echo e(__('messages.grade')); ?></th>
                                            <th width="10%"><?php echo e(__('messages.image')); ?></th>
                                            <th width="17%"><?php echo e(__('messages.actions')); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $exam->questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td>
                                                    <span class="badge bg-primary"><?php echo e($question->order); ?></span>
                                                </td>
                                                <td>
                                                    <div class="question-preview">
                                                        <?php echo e(Str::limit($question->question_text, 80)); ?>

                                                    </div>
                                                </td>
                                                <td>
                                                    <?php switch($question->type):
                                                        case ('multiple_choice'): ?>
                                                            <span class="badge bg-info"><?php echo e(__('messages.multiple_choice')); ?></span>
                                                            <?php break; ?>
                                                        <?php case ('true_false'): ?>
                                                            <span class="badge bg-success"><?php echo e(__('messages.true_false')); ?></span>
                                                            <?php break; ?>
                                                        <?php case ('essay'): ?>
                                                            <span class="badge bg-warning"><?php echo e(__('messages.essay')); ?></span>
                                                            <?php break; ?>
                                                        <?php case ('fill_blank'): ?>
                                                            <span class="badge bg-secondary"><?php echo e(__('messages.fill_blank')); ?></span>
                                                            <?php break; ?>
                                                    <?php endswitch; ?>
                                                </td>
                                                <td><?php echo e($question->grade); ?></td>
                                                <td>
                                                    <?php if($question->question_image): ?>
                                                        <i class="fas fa-image text-success" title="<?php echo e(__('messages.has_image')); ?>"></i>
                                                    <?php else: ?>
                                                        <i class="fas fa-times text-muted" title="<?php echo e(__('messages.no_image')); ?>"></i>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="<?php echo e(route('questions.show', [$exam, $question])); ?>" 
                                                           class="btn btn-info" title="<?php echo e(__('messages.view')); ?>">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="<?php echo e(route('questions.edit', [$exam, $question])); ?>" 
                                                           class="btn btn-warning" title="<?php echo e(__('messages.edit')); ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="<?php echo e(route('questions.destroy', [$exam, $question])); ?>" 
                                                              method="POST" style="display: inline;">
                                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" class="btn btn-danger" 
                                                                    title="<?php echo e(__('messages.delete')); ?>"
                                                                    onclick="return confirm('<?php echo e(__('messages.confirm_delete')); ?>')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle"></i>
                                <?php echo e(__('messages.no_questions_found')); ?>

                                <br>
                                <a href="<?php echo e(route('questions.create', $exam)); ?>" class="btn btn-primary mt-2">
                                    <?php echo e(__('messages.add_first_question')); ?>

                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if(Auth::check() && $exam->canUserAttempt(Auth::id())): ?>
                        <div class="mt-4 text-center">
                            <form action="<?php echo e(route('exam.take', $exam)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-play"></i> <?php echo e(__('messages.take_exam')); ?>

                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\platform\resources\views/admin/exams/show.blade.php ENDPATH**/ ?>