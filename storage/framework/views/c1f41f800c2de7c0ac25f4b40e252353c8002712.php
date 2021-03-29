<?php $__env->startSection('title',__('admin.pages.login.title').' | '.__('admin.site_name')); ?>

<?php $__env->startSection('head'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    
    <form action="/admin/login" method="POST">
        <?php echo e(csrf_field()); ?>

        <div class="input-group">
            <input type="email" name="email" placeholder="<?php echo e(__('admin.labels.email')); ?>" class="input-regular"
                   required value="<?php echo e(old('email')); ?>">
        </div>
        <div class="input-group">
            <input type="password" name="password" placeholder="<?php echo e(__('admin.labels.password')); ?>"
                   class="input-regular" required value="<?php echo e(old('password')); ?>">
            <div class="text-right"><a href="/<?php echo e($lang); ?>/admin/passwordReset"
                                       title="<?php echo __('admin.pages.password_reset.title'); ?>"
                                       class="grey-link small"><?php echo __('admin.pages.password_reset.title'); ?></a>
            </div>
        </div>
        <div class="input-group">
            <button class="btn" type="submit" style="width: 100%;"><?php echo e(__('admin.pages.login.submit')); ?></button>
        </div>
    </form>
    <?php echo $__env->make('admin.v2.partials.components.errors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('admin.v2.partials.components.success',['message' => session('password_reset')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.v2.layout.auth.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\OpenServer\domains\enbek\resources\views/admin/v2/pages/auth/login.blade.php ENDPATH**/ ?>