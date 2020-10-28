<!DOCTYPE html>
<html lang="<?php echo e($lang); ?>">
<head>
    <?php echo $__env->make('admin.v2.layout.default.components.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->yieldContent('head'); ?>
</head>
<body>
<input id="lang" type="hidden" value="ru">
<div class="authorization-wrapper">
    <div class="authorization-inner">
        <?php echo $__env->yieldContent('content'); ?>
    </div>
    <div class="copyright">Canvas Technologies, 2011-<?php echo e(date('Y')); ?></div>
</div>
<?php echo $__env->make('admin.v2.layout.default.components.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\OpenServer\domains\enbek\resources\views/admin/v2/layout/auth/template.blade.php ENDPATH**/ ?>