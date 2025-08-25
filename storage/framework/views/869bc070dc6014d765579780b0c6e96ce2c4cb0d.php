


<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4><?php echo e(__('messages.exam_categories')); ?></h4>
                    <a href="<?php echo e(route('category_exams.create')); ?>" class="btn btn-primary">
                        <?php echo e(__('messages.add_new_category')); ?>

                    </a>
                </div>

                <div class="card-body">
                  

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('messages.id')); ?></th>
                                    <th><?php echo e(__('messages.name')); ?></th>
                                    <th><?php echo e(__('messages.parent_category')); ?></th>
                                    <th><?php echo e(__('messages.children_count')); ?></th>
                                    <th><?php echo e(__('messages.created_at')); ?></th>
                                    <th><?php echo e(__('messages.actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($category->id); ?></td>
                                        <td><?php echo e($category->name); ?></td>
                                        <td><?php echo e($category->parent ? $category->parent->name : __('messages.no_parent')); ?></td>
                                        <td><?php echo e($category->children->count()); ?></td>
                                        <td><?php echo e($category->created_at->format('Y-m-d')); ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                             
                                                <a href="<?php echo e(route('category_exams.edit', $category)); ?>" class="btn btn-sm btn-warning">
                                                    <?php echo e(__('messages.edit')); ?>

                                                </a>
                                                <form action="<?php echo e(route('category_exams.destroy', $category)); ?>" method="POST" style="display: inline;">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
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
                                        <td colspan="6" class="text-center"><?php echo e(__('messages.no_categories_found')); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        <?php echo e($categories->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\platform\resources\views/admin/category_exams/index.blade.php ENDPATH**/ ?>