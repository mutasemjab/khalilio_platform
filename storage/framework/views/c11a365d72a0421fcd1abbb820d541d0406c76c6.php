


<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4><?php echo e(__('messages.edit_question')); ?>: <?php echo e($exam->name); ?></h4>
                </div>

                <div class="card-body">
                    <form action="<?php echo e(route('questions.update', [$exam, $question])); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="type" class="form-label"><?php echo e(__('messages.question_type')); ?> <span class="text-danger">*</span></label>
                                <select class="form-control <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="type" name="type" required onchange="toggleQuestionFields()">
                                    <option value=""><?php echo e(__('messages.select_question_type')); ?></option>
                                    <option value="multiple_choice" <?php echo e(old('type', $question->type) == 'multiple_choice' ? 'selected' : ''); ?>>
                                        <?php echo e(__('messages.multiple_choice')); ?>

                                    </option>
                                    <option value="true_false" <?php echo e(old('type', $question->type) == 'true_false' ? 'selected' : ''); ?>>
                                        <?php echo e(__('messages.true_false')); ?>

                                    </option>
                                    <option value="essay" <?php echo e(old('type', $question->type) == 'essay' ? 'selected' : ''); ?>>
                                        <?php echo e(__('messages.essay')); ?>

                                    </option>
                                    <option value="fill_blank" <?php echo e(old('type', $question->type) == 'fill_blank' ? 'selected' : ''); ?>>
                                        <?php echo e(__('messages.fill_blank')); ?>

                                    </option>
                                </select>
                                <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-3">
                                <label for="grade" class="form-label"><?php echo e(__('messages.grade')); ?> <span class="text-danger">*</span></label>
                                <input type="number" step="0.1" class="form-control <?php $__errorArgs = ['grade'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="grade" name="grade" value="<?php echo e(old('grade', $question->grade)); ?>" min="0.1" required>
                                <?php $__errorArgs = ['grade'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-3">
                                <label for="order" class="form-label"><?php echo e(__('messages.order')); ?> <span class="text-danger">*</span></label>
                                <input type="number" class="form-control <?php $__errorArgs = ['order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="order" name="order" value="<?php echo e(old('order', $question->order)); ?>" min="1" required>
                                <?php $__errorArgs = ['order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="question_text" class="form-label"><?php echo e(__('messages.question_text')); ?> <span class="text-danger">*</span></label>
                            <textarea class="form-control <?php $__errorArgs = ['question_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="question_text" name="question_text" rows="4" required><?php echo e(old('question_text', $question->question_text)); ?></textarea>
                            <?php $__errorArgs = ['question_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-3">
                            <label for="question_image" class="form-label"><?php echo e(__('messages.question_image')); ?></label>
                            <?php if($question->question_image): ?>
                                <div class="mb-2">
                                    <img src="<?php echo e(asset('assets/admin/uploads/' . $question->question_image)); ?>" 
                                         alt="Current Image" class="img-thumbnail" style="max-height: 150px;">
                                    <p class="small text-muted"><?php echo e(__('messages.current_image')); ?></p>
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control <?php $__errorArgs = ['question_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="question_image" name="question_image" accept="image/*">
                            <small class="text-muted"><?php echo e(__('messages.leave_empty_keep_current')); ?></small>
                            <?php $__errorArgs = ['question_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Multiple Choice Options -->
                        <div id="multiple_choice_fields" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label"><?php echo e(__('messages.answer_options')); ?> <span class="text-danger">*</span></label>
                                <div id="options_container">
                                    <?php if(old('options') || ($question->type == 'multiple_choice' && $question->options)): ?>
                                        <?php
                                            $options = old('options') ?: $question->options;
                                            $correctAnswers = old('correct_answers') ?: $question->correct_answers ?: [];
                                        ?>
                                        <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="row mb-2">
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" name="options[]" 
                                                           placeholder="<?php echo e(__('messages.option')); ?> <?php echo e($index + 1); ?>" 
                                                           value="<?php echo e($option); ?>">
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" 
                                                               name="correct_answers[]" value="<?php echo e($index); ?>"
                                                               <?php echo e(in_array($index, $correctAnswers) ? 'checked' : ''); ?>>
                                                        <label class="form-check-label"><?php echo e(__('messages.correct')); ?></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="removeOption(this)">×</button>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        <div class="row mb-2">
                                            <div class="col-md-8">
                                                <input type="text" class="form-control" name="options[]" placeholder="<?php echo e(__('messages.option')); ?> 1">
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="correct_answers[]" value="0">
                                                    <label class="form-check-label"><?php echo e(__('messages.correct')); ?></label>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-sm btn-danger" onclick="removeOption(this)">×</button>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <button type="button" class="btn btn-sm btn-secondary" onclick="addOption()"><?php echo e(__('messages.add_option')); ?></button>
                            </div>
                        </div>

                        <!-- True/False Options -->
                        <div id="true_false_fields" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label"><?php echo e(__('messages.correct_answer')); ?> <span class="text-danger">*</span></label>
                                <div>
                                    <?php
                                        $correctAnswer = old('correct_answers') ?: (isset($question->correct_answers[0]) ? $question->correct_answers[0] : null);
                                    ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="correct_answers" 
                                               id="true_answer" value="true" <?php echo e($correctAnswer === 'true' ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="true_answer"><?php echo e(__('messages.true')); ?></label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="correct_answers" 
                                               id="false_answer" value="false" <?php echo e($correctAnswer === 'false' ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="false_answer"><?php echo e(__('messages.false')); ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="explanation" class="form-label"><?php echo e(__('messages.explanation')); ?></label>
                            <textarea class="form-control <?php $__errorArgs = ['explanation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="explanation" name="explanation" rows="2" 
                                      placeholder="<?php echo e(__('messages.explanation_placeholder')); ?>"><?php echo e(old('explanation', $question->explanation)); ?></textarea>
                            <?php $__errorArgs = ['explanation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?php echo e(route('questions.show', [$exam, $question])); ?>" class="btn btn-secondary">
                                <?php echo e(__('messages.cancel')); ?>

                            </a>
                            <button type="submit" class="btn btn-primary">
                                <?php echo e(__('messages.update_question')); ?>

                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let optionCount = <?php echo e(count($question->options ?? []) ?: 1); ?>;

function toggleQuestionFields() {
    const type = document.getElementById('type').value;
    const mcFields = document.getElementById('multiple_choice_fields');
    const tfFields = document.getElementById('true_false_fields');
    
    // Hide all fields first
    mcFields.style.display = 'none';
    tfFields.style.display = 'none';
    
    // Show relevant fields
    if (type === 'multiple_choice') {
        mcFields.style.display = 'block';
    } else if (type === 'true_false') {
        tfFields.style.display = 'block';
    }
}

function addOption() {
    optionCount++;
    const container = document.getElementById('options_container');
    const newOption = document.createElement('div');
    newOption.className = 'row mb-2';
    newOption.innerHTML = `
        <div class="col-md-8">
            <input type="text" class="form-control" name="options[]" placeholder="<?php echo e(__('messages.option')); ?> ${optionCount}">
        </div>
        <div class="col-md-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="correct_answers[]" value="${optionCount - 1}">
                <label class="form-check-label"><?php echo e(__('messages.correct')); ?></label>
            </div>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-sm btn-danger" onclick="removeOption(this)">×</button>
        </div>
    `;
    container.appendChild(newOption);
}

function removeOption(button) {
    if (document.getElementById('options_container').children.length > 1) {
        button.closest('.row').remove();
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleQuestionFields();
});
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\platform\resources\views/admin/questions/edit.blade.php ENDPATH**/ ?>