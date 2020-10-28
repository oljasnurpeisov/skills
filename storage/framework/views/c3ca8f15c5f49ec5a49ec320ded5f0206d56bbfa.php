<!DOCTYPE html>
<html lang="<?php echo e($lang); ?>">
<head>
    <?php echo $__env->make('admin.v2.layout.default.components.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->yieldContent('head'); ?>
</head>
<body>
<input id="lang" type="hidden" value="ru">
<div class="main-wrapper">
    <?php echo $__env->make('admin.v2.layout.default.components.navigation.left', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="right-wrapper">
        <?php echo $__env->make('admin.v2.layout.default.components.navigation.top', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <main class="main">
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>
</div>
<?php echo $__env->make('admin.v2.layout.default.components.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('admin.v2.layout.default.components.modals', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\OpenServer\domains\enbek\resources\views/admin/v2/layout/default/template.blade.php ENDPATH**/ ?>