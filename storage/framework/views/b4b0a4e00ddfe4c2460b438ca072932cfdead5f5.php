

<?php $__env->startSection('content'); ?>
<div id="loginSection" class="register-form">
    <div class="card">
        <h2 style="text-align: center; margin-bottom: 2rem; color: var(--text-dark); font-size: 2rem;">
            <i class="fas fa-sign-in-alt" style="color: var(--primary-color); margin-left: 0.5rem;"></i>
            تسجيل الدخول
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

        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>
        
        <form method="POST" action="<?php echo e(route('login')); ?>" id="loginForm">
            <?php echo csrf_field(); ?>
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

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-sign-in-alt"></i>
                تسجيل الدخول
            </button>
        </form>

        <!-- Registration link section -->
        <div style="text-align: center; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #e0e0e0;">
            <p style="margin-bottom: 1rem; color: var(--text-muted);">
                ليس لديك حساب؟
            </p>
            <a href="<?php echo e(route('register')); ?>" class="btn btn-outline-primary" style="text-decoration: none;">
                <i class="fas fa-user-plus"></i>
                إنشاء حساب جديد
            </a>
        </div>
    </div>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
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
<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\platform\resources\views/sections/login.blade.php ENDPATH**/ ?>