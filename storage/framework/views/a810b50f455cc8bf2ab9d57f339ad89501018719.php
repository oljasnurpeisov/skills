<?php $__env->startSection('title',__('admin.pages.password_reset.title').' | '.__('admin.site_name')); ?>

<?php $__env->startSection('head'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    
    <form action="/<?php echo e($lang); ?>/admin/passwordReset" method="POST">
        <?php echo e(csrf_field()); ?>

        <div class="input-group">
            <input type="email" name="email" placeholder="<?php echo e(__('admin.labels.email')); ?>" class="input-regular"
                   required value="<?php echo e(old('email')); ?>">
            <div class="text-right">
                <a href="/<?php echo e($lang); ?>/admin/login" title="<?php echo __('admin.pages.login.title'); ?>"
                   class="grey-link small"><?php echo __('admin.pages.login.title'); ?></a>
            </div>
        </div>
        <div class="input-group">
            <button class="btn" type="submit" style="width: 100%;"><?php echo e(__('admin.pages.password_reset.submit')); ?></button>
        </div>
    </form>
    <?php echo $__env->make('admin.v2.partials.components.errors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.v2.layout.auth.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\OpenServer\domains\enbek\resources\views/admin/v2/pages/auth/password_reset.blade.php ENDPATH**/ ?>