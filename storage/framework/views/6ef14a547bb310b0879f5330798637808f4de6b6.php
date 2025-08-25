

<?php $__env->startSection('content'); ?>
<div id="registrationSection" class="register-form">
    <div class="card">
        <h2 style="text-align: center; margin-bottom: 2rem; color: var(--text-dark); font-size: 2rem;">
            <i class="fas fa-user-plus" style="color: var(--primary-color); margin-left: 0.5rem;"></i>
            إنشاء حساب جديد
        </h2>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-right: 1rem;">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>
        
        <form method="POST" action="<?php echo e(route('register')); ?>" id="registrationForm">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-user"></i> الاسم الكامل
                </label>
                <input type="text" name="name" class="form-control" required 
                       placeholder="أدخل اسمك الكامل" value="<?php echo e(old('name')); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-phone"></i> رقم الهاتف
                </label>
                <input type="tel" name="phone" class="form-control" required 
                       placeholder="07xxxxxxxx" value="<?php echo e(old('phone')); ?>"
                       pattern="^07[0-9]{8}$"
                       maxlength="10"
                       title="رقم الهاتف يجب أن يكون 10 أرقام ويبدأ بـ 07">
                <small class="form-text text-muted">مثال: 0791234567</small>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-school"></i> اسم المدرسة
                </label>
                <input type="text" name="school_name" class="form-control" required 
                       placeholder="أدخل اسم مدرستك" value="<?php echo e(old('school_name')); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-layer-group"></i> المجال الدراسي
                </label>
                <select name="field_id" class="form-control" required>
                    <option value="">اختر المجال الدراسي</option>
                    <?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($field->id); ?>" <?php echo e(old('field_id') == $field->id ? 'selected' : ''); ?>>
                            <?php echo e($field->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-check"></i>
                إنشاء الحساب
            </button>
        </form>
    </div>
</div>

<script>
document.getElementById('registrationForm').addEventListener('submit', function(e) {
    const phone = document.querySelector('input[name="phone"]').value;
    const phoneRegex = /^07[0-9]{8}$/;
    
    if (!phoneRegex.test(phone)) {
        e.preventDefault();
        alert('رقم الهاتف يجب أن يكون 10 أرقام ويبدأ بـ 07');
        return false;
    }
});

// Real-time validation feedback
document.querySelector('input[name="phone"]').addEventListener('input', function() {
    const phone = this.value;
    const phoneRegex = /^07[0-9]{8}$/;
    
    if (phone.length > 0 && !phoneRegex.test(phone)) {
        this.style.borderColor = '#e74c3c';
    } else if (phoneRegex.test(phone)) {
        this.style.borderColor = '#27ae60';
    } else {
        this.style.borderColor = '';
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\platform\resources\views/sections/registration.blade.php ENDPATH**/ ?>