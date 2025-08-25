


<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4><?php echo e(__('messages.lessons_management')); ?></h4>
                        <div>

                            <a href="<?php echo e(route('lessons.create')); ?>" class="btn btn-primary">
                                <i class="fas fa-plus"></i> <?php echo e(__('messages.add_new_lesson')); ?>

                            </a>

                        </div>
                    </div>

                    <div class="card-body">


                        <!-- Filters -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <select class="form-control" id="categoryFilter" onchange="filterLessons()">
                                    <option value=""><?php echo e(__('messages.all_categories')); ?></option>
                                    <?php
                                        $categories = \App\Models\CategoryLesson::all();
                                    ?>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="searchFilter"
                                    placeholder="<?php echo e(__('messages.search_lessons')); ?>" onkeyup="filterLessons()">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                                    <?php echo e(__('messages.clear_filters')); ?>

                                </button>
                            </div>
                        </div>

                        
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>

                                        <th width="5%"><?php echo e(__('messages.id')); ?></th>
                                        <th width="15%"><?php echo e(__('messages.thumbnail')); ?></th>
                                        <th width="30%"><?php echo e(__('messages.lesson_name')); ?></th>
                                        <th width="20%"><?php echo e(__('messages.category')); ?></th>
                                        <th width="15%"><?php echo e(__('messages.created_at')); ?></th>
                                        <th width="10%"><?php echo e(__('messages.actions')); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>

                                            <td><?php echo e($lesson->id); ?></td>
                                            <td>
                                                <?php if($lesson->getYoutubeThumbnailAttribute()): ?>
                                                    <img src="<?php echo e($lesson->getYoutubeThumbnailAttribute()); ?>"
                                                        alt="Video Thumbnail" class="img-thumbnail"
                                                        style="width: 80px; height: 60px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="bg-light d-flex align-items-center justify-content-center"
                                                        style="width: 80px; height: 60px;">
                                                        <i class="fas fa-video text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong><?php echo e($lesson->name); ?></strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="fab fa-youtube text-danger"></i>
                                                        <?php echo e(__('messages.video_id')); ?>:
                                                        <?php echo e($lesson->getYoutubeIdAttribute() ?? __('messages.invalid_url')); ?>

                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-info"><?php echo e($lesson->category->name ?? __('messages.no_category')); ?></span>
                                            </td>
                                            <td>
                                                <div>
                                                    <?php echo e($lesson->created_at->format('Y-m-d')); ?>

                                                    <br>
                                                    <small
                                                        class="text-muted"><?php echo e($lesson->created_at->format('H:i')); ?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                   
                                                    <a href="<?php echo e(route('lessons.edit', $lesson)); ?>" class="btn btn-warning"
                                                        title="<?php echo e(__('messages.edit')); ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="<?php echo e(route('lessons.destroy', $lesson)); ?>" method="POST"
                                                        style="display: inline;">
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
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <i class="fas fa-video text-muted"></i>
                                                <p class="mt-2"><?php echo e(__('messages.no_lessons_found')); ?></p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <?php if($lessons->hasPages()): ?>
                            <div class="d-flex justify-content-center mt-3">
                                <?php echo e($lessons->links()); ?>

                            </div>
                        <?php endif; ?>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function filterLessons() {
            const categoryId = document.getElementById('categoryFilter').value;
            const search = document.getElementById('searchFilter').value;

            fetch(`<?php echo e(route('lessons.search')); ?>`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        category_id: categoryId,
                        search: search
                    })
                })
                .then(response => response.text())
                .then(html => {
                    document.getElementById('lessonsContainer').innerHTML = html;
                    toggleBulkDelete();
                })
                .catch(error => console.error('Error:', error));
        }

        function clearFilters() {
            document.getElementById('categoryFilter').value = '';
            document.getElementById('searchFilter').value = '';
            filterLessons();
        }


        // Add event listeners to checkboxes
        document.addEventListener('DOMContentLoaded', function() {
            toggleBulkDelete();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\platform\resources\views/admin/lessons/index.blade.php ENDPATH**/ ?>