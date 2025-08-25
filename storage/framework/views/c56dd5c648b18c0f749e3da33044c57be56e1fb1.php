


<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4><?php echo e(__('messages.files_management')); ?></h4>
                    <div>
                        <a href="<?php echo e(route('files.create')); ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> <?php echo e(__('messages.add_new_file')); ?>

                        </a>
                        <button class="btn btn-danger" id="bulkDeleteBtn" style="display: none;" onclick="bulkDelete()">
                            <i class="fas fa-trash"></i> <?php echo e(__('messages.delete_selected')); ?>

                        </button>
                    </div>
                </div>

                <div class="card-body">

                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <select class="form-control" id="categoryFilter" onchange="filterFiles()">
                                <option value=""><?php echo e(__('messages.all_categories')); ?></option>
                                <?php if(isset($categories)): ?>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <?php
                                        $categories = \App\Models\CategoryFile::all();
                                    ?>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="searchFilter" 
                                   placeholder="<?php echo e(__('messages.search_files')); ?>" onkeyup="filterFiles()">
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
                                    <th width="30%"><?php echo e(__('messages.file_name')); ?></th>
                                    <th width="20%"><?php echo e(__('messages.category')); ?></th>
                                    <th width="15%"><?php echo e(__('messages.created_at')); ?></th>
                                    <th width="15%"><?php echo e(__('messages.actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                      
                                        <td><?php echo e($file->id); ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-file-pdf text-danger me-2"></i>
                                                <div>
                                                    <strong><?php echo e($file->name); ?></strong>
                                                    <br>
                                                    <small class="text-muted"><?php echo e($file->pdf); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info"><?php echo e($file->category->name ?? __('messages.no_category')); ?></span>
                                        </td>
                                       
                                        <td>
                                            <div>
                                                <?php echo e($file->created_at->format('Y-m-d')); ?>

                                                <br>
                                                <small class="text-muted"><?php echo e($file->created_at->format('H:i')); ?></small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                               
                                              
                                                
                                                <a href="<?php echo e(route('files.edit', $file)); ?>" class="btn btn-warning" title="<?php echo e(__('messages.edit')); ?>">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="<?php echo e(route('files.destroy', $file)); ?>" method="POST" style="display: inline;">
                                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-danger" title="<?php echo e(__('messages.delete')); ?>"
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
                                            <i class="fas fa-folder-open text-muted"></i>
                                            <p class="mt-2"><?php echo e(__('messages.no_files_found')); ?></p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if($files->hasPages()): ?>
                        <div class="d-flex justify-content-center mt-3">
                            <?php echo e($files->links()); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function filterFiles() {
    const categoryId = document.getElementById('categoryFilter').value;
    const search = document.getElementById('searchFilter').value;
    
    fetch(`<?php echo e(route('files.search')); ?>`, {
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
        document.getElementById('filesContainer').innerHTML = html;
        toggleBulkDelete();
    })
    .catch(error => console.error('Error:', error));
}

function clearFilters() {
    document.getElementById('categoryFilter').value = '';
    document.getElementById('searchFilter').value = '';
    filterFiles();
}

// Add event listeners to checkboxes
document.addEventListener('DOMContentLoaded', function() {
    toggleBulkDelete();
    
    // Add event listeners to existing checkboxes
    document.querySelectorAll('input[name="selected_files[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', toggleBulkDelete);
    });
});
</script>
<?php $__env->stopSection(); ?>




<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\platform\resources\views/admin/files/index.blade.php ENDPATH**/ ?>