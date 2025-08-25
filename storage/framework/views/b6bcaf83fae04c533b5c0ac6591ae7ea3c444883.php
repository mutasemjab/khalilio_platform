


<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4><?php echo e(__('messages.exam_questions')); ?>: <?php echo e($exam->name); ?></h4>
                        <small class="text-muted"><?php echo e(__('messages.total_grade')); ?>: <?php echo e($exam->total_grade); ?></small>
                    </div>
                    <div>
                        <a href="<?php echo e(route('questions.create', $exam)); ?>" class="btn btn-primary">
                            <?php echo e(__('messages.add_question')); ?>

                        </a>
                        <a href="<?php echo e(route('exams.show', $exam)); ?>" class="btn btn-secondary">
                            <?php echo e(__('messages.back_to_exam')); ?>

                        </a>
                    </div>
                </div>

                <div class="card-body">
                  

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('messages.order')); ?></th>
                                    <th><?php echo e(__('messages.question')); ?></th>
                                    <th><?php echo e(__('messages.type')); ?></th>
                                    <th><?php echo e(__('messages.grade')); ?></th>
                                    <th><?php echo e(__('messages.image')); ?></th>
                                    <th><?php echo e(__('messages.actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody id="questions-table">
                                <?php $__empty_1 = true; $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr data-id="<?php echo e($question->id); ?>">
                                        <td>
                                            <span class="badge bg-primary"><?php echo e($question->order); ?></span>
                                        </td>
                                        <td>
                                            <div class="question-preview">
                                                <?php echo e(Str::limit($question->question_text, 100)); ?>

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
                                                <i class="fas fa-image text-success"></i>
                                            <?php else: ?>
                                                <i class="fas fa-times text-muted"></i>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo e(route('questions.show', [$exam, $question])); ?>" class="btn btn-sm btn-info">
                                                    <?php echo e(__('messages.view')); ?>

                                                </a>
                                                <a href="<?php echo e(route('questions.edit', [$exam, $question])); ?>" class="btn btn-sm btn-warning">
                                                    <?php echo e(__('messages.edit')); ?>

                                                </a>
                                                <form action="<?php echo e(route('questions.duplicate', [$exam, $question])); ?>" method="POST" style="display: inline;">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="btn btn-sm btn-secondary">
                                                        <?php echo e(__('messages.duplicate')); ?>

                                                    </button>
                                                </form>
                                                <form action="<?php echo e(route('questions.destroy', [$exam, $question])); ?>" method="POST" style="display: inline;">
                                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-sm btn-danger" 
                                                            onclick="return confirm('<?php echo e(__('messages.confirm_delete')); ?>')">
                                                        <?php echo e(__('messages.delete')); ?>

                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="6" class="text-center"><?php echo e(__('messages.no_questions_found')); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        <?php echo e($questions->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\platform\resources\views/admin/questions/index.blade.php ENDPATH**/ ?>