<header class="header">
    <div class="container container-fluid">
        <a href="javascript:;" title="Свернуть/развернуть навигацию" class="menu-btn icon-menu"></a>
        <a href="/admin" title="Главная" class="logo hidden-md hidden-lg">
            
        </a>
        <div class="language hidden-sm hidden-xs">

        </div>
        <div class="header-dropdown account-nav">
            <div class="header-dropdown__title">
                <?php ($user = \Illuminate\Support\Facades\Auth::user()); ?>
                <span><?php echo e(__('admin.labels.welcome')); ?>, <?php echo e($user !== null ? $user->name : ''); ?>!</span>
                <img src="/assets/admin/img/user.svg" alt=""> <i class="icon-chevron-down"></i>
            </div>
            <div class="header-dropdown__desc">
                <ul>
                    <li><a href="/admin/profile/">Профиль</a></li>
                    <li>
                        <a href="/admin/logout"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                    class="fa fa-sign-out pull-right"></i><?php echo e(__('admin.labels.logout')); ?></a>
                        <form id="logout-form" action="<?php echo e(url('/admin/logout')); ?>" method="POST"
                              style="display: none;">
                            <?php echo e(csrf_field()); ?>

                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
<?php /**PATH D:\OpenServer\domains\enbek\resources\views/admin/v2/layout/default/components/navigation/top.blade.php ENDPATH**/ ?>