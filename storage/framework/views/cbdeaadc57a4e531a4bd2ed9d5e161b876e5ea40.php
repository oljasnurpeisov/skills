<?php $__env->startSection('title','Профиль | '.__('default.site_name')); ?>

<?php $__env->startSection('content'); ?>
    <div class="container container-fluid">
        <ul class="breadcrumbs">
            <li class="active">Профиль</li>
        </ul>
        <?php echo $__env->make('admin.v2.partials.components.warning', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('admin.v2.partials.components.errors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <form action="/<?php echo e($lang); ?>/admin/profile" method="post"
              enctype="multipart/form-data">
            <?php echo e(csrf_field()); ?>

            <div class="block">
                <h2 class="title-secondary">Сменить контактные данные</h2>
                
                    
                                
                    
                           
                           
                    
                        
                    
                
                <div class="input-group <?php echo e($errors->has('name') ? ' has-error' : ''); ?>">
                    <label class="input-group__title"><?php echo e(__('admin.pages.user.name')); ?> <span
                                class="required">*</span></label>
                    <input type="text" name="name" value="<?php echo e($item->name); ?>"
                           placeholder="<?php echo e(__('admin.labels.fill_field',['field' => __('admin.pages.user.name')])); ?>"
                           class="input-regular" required>
                    <?php if($errors->has('name')): ?>
                        <span class="help-block"><strong><?php echo e($errors->first('name')); ?></strong></span>
                    <?php endif; ?>
                </div>
                
                    
                    
                           
                           
                    
                        
                    
                
                <div class="input-group <?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
                    <label class="input-group__title"><?php echo e(__('admin.pages.user.email')); ?></label>
                    <input type="email" name="email" value="<?php echo e($item->email); ?>"
                           placeholder="<?php echo e(__('admin.labels.fill_field',['field' => __('admin.pages.user.email')])); ?>"
                           class="input-regular" required>
                    <?php if($errors->has('email')): ?>
                        <span class="help-block"><strong><?php echo e($errors->first('email')); ?></strong></span>
                    <?php endif; ?>
                </div>
                <div class="buttons">
                    <div>
                        <button type="submit" class="btn btn--green"><?php echo e(__('admin.labels.save')); ?></button>
                    </div>
                </div>
            </div>
        </form>
        <form action="/<?php echo e($lang); ?>/admin/profile" method="post"
              enctype="multipart/form-data">
            <?php echo e(csrf_field()); ?>

            <div class="block">
                <h2 class="title-secondary">Сменить пароль</h2>
                <div class="input-group <?php echo e($errors->has('old_password') ? ' has-error' : ''); ?>">
                    <label class="input-group__title">Старый пароль *</label>
                    <input type="password" name="old_password"
                           placeholder="<?php echo e(__('admin.labels.fill_field',['field' => 'Старый пароль'])); ?>"
                           class="input-regular" required>
                    <?php if($errors->has('old_password')): ?>
                        <span class="help-block"><strong><?php echo $errors->first('old_password'); ?></strong></span>
                    <?php endif; ?>
                </div>
                <div class="input-group <?php echo e($errors->has('password') ? ' has-error' : ''); ?>">
                    <label class="input-group__title">Новый пароль *</label>
                    <input type="password" name="password"
                           placeholder="<?php echo e(__('admin.labels.fill_field',['field' => 'Новый пароль'])); ?>"
                           class="input-regular" required>
                    <?php if($errors->has('password')): ?>
                        <span class="help-block"><strong><?php echo $errors->first('password'); ?></strong></span>
                    <?php endif; ?>
                </div>
                <div class="input-group <?php echo e($errors->has('password_confirmation') ? ' has-error' : ''); ?>">
                    <label class="input-group__title">Подтвердите пароль *</label>
                    <input type="password" name="password_confirmation"
                           placeholder="<?php echo e(__('admin.labels.fill_field',['field' => 'Подтвердите пароль'])); ?>"
                           class="input-regular" required>
                    <?php if($errors->has('password_confirmation')): ?>
                        <span class="help-block"><strong><?php echo $errors->first('password_confirmation'); ?></strong></span>
                    <?php endif; ?>
                </div>
                <div class="buttons">
                    <div>
                        <button type="submit" class="btn btn--green"><?php echo e(__('admin.labels.save')); ?></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.v2.layout.default.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\OpenServer\domains\enbek\resources\views/admin/v2/pages/users/profile.blade.php ENDPATH**/ ?>