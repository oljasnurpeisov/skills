<aside class="sidebar">
    <div class="sidebar__top hidden-sm hidden-xs">
        
        <a href="/admin" title="Главная" class="logo" style="color:white"><img src="" alt="">logo</a>
    </div>
    <div class="menu-wrapper">
        <ul class="menu">
            <?php if (\Illuminate\Support\Facades\Blade::check('hasPermission', 'admin.roles')): ?>
            <li class="dropdown">
                <a href="javascript:;" title="<?php echo e(__('admin.pages.roles.title')); ?>">
                    <i class="icon-users"></i> <?php echo e(__('admin.pages.roles.title')); ?>

                </a>
                <ul>
                    <li><a href="/<?php echo e($lang); ?>/admin/role/index"><?php echo e(__('admin.pages.roles.list')); ?></a></li>
                    <li><a href="/<?php echo e($lang); ?>/admin/role/create" class="add">+<?php echo e(__('admin.pages.roles.create')); ?></a></li>
                </ul>
            </li>
            <?php endif; ?>
            <?php if (\Illuminate\Support\Facades\Blade::check('hasPermission', 'admin.users')): ?>
            <li class="dropdown">
                <a href="javascript:;" title="<?php echo e(__('admin.pages.users.title')); ?>">
                    <i class="icon-users"></i> <?php echo e(__('admin.pages.users.title')); ?>

                </a>
                <ul>
                    <li><a href="/<?php echo e($lang); ?>/admin/user/index"><?php echo e(__('admin.pages.users.list')); ?></a></li>
                    <li><a href="/<?php echo e($lang); ?>/admin/user/create" class="add">+<?php echo e(__('admin.pages.users.create')); ?></a></li>
                </ul>
            </li>
            <?php endif; ?>
            <?php if (\Illuminate\Support\Facades\Blade::check('hasPermission', 'admin.authors')): ?>
            <li class="dropdown">
                <a href="javascript:;" title="<?php echo e(__('admin.pages.authors.title')); ?>">
                    <i class="icon-users"></i> <?php echo e(__('admin.pages.authors.title')); ?>

                </a>
                <ul>
                    <li><a href="/<?php echo e($lang); ?>/admin/author/index"><?php echo e(__('admin.pages.authors.list')); ?></a></li>
                    
                </ul>
            </li>
            <?php endif; ?>

        </ul>
    </div>
</aside>
<?php /**PATH D:\OpenServer\domains\enbek\resources\views/admin/v2/layout/default/components/navigation/left.blade.php ENDPATH**/ ?>