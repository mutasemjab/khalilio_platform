


<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4><?php echo e(__('messages.exams')); ?></h4>
                    <a href="<?php echo e(route('exams.create')); ?>" class="btn btn-primary">
                        <?php echo e(__('messages.create_new_exam')); ?>

                    </a>
                </div>

                <div class="card-body">
                   

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('messages.id')); ?></th>
                                    <th><?php echo e(__('messages.exam_name')); ?></th>
                                    <th><?php echo e(__('messages.category')); ?></th>
                                    <th><?php echo e(__('messages.duration')); ?></th>
                                    <th><?php echo e(__('messages.total_grade')); ?></th>
                                    <th><?php echo e(__('messages.questions_count')); ?></th>
                                    <th><?php echo e(__('messages.status')); ?></th>
                                    <th><?php echo e(__('messages.actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($exam->id); ?></td>
                                        <td><?php echo e($exam->name); ?></td>
                                        <td><?php echo e($exam->category->name ?? __('messages.no_category')); ?></td>
                                        <td><?php echo e($exam->duration_minutes); ?> <?php echo e(__('messages.minutes')); ?></td>
                                        <td><?php echo e($exam->total_grade); ?></td>
                                        <td><?php echo e($exam->questions->count()); ?></td>
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
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo e(route('exams.show', $exam)); ?>" class="btn btn-sm btn-info">
                                                    <?php echo e(__('messages.view')); ?>

                                                </a>
                                                <a href="<?php echo e(route('questions.index', $exam)); ?>" class="btn btn-sm btn-primary">
                                                    <?php echo e(__('messages.questions')); ?>

                                                </a>
                                                <a href="<?php echo e(route('exams.edit', $exam)); ?>" class="btn btn-sm btn-warning">
                                                    <?php echo e(__('messages.edit')); ?>

                                                </a>
                                                <a href="<?php echo e(route('exams.attempts', $exam)); ?>" class="btn btn-sm btn-secondary">
                                                    <?php echo e(__('messages.attempts')); ?>

                                                </a>
                                                <form action="<?php echo e(route('exams.destroy', $exam)); ?>" method="POST" style="display: inline;">
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
                                        <td colspan="8" class="text-center"><?php echo e(__('messages.no_exams_found')); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        <?php echo e($exams->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\platform\resources\views/admin/exams/index.blade.php ENDPATH**/ ?>