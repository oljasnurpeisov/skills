<?php $__env->startSection('title',$item->name.' | '.__('admin.site_name')); ?>

<?php $__env->startSection('content'); ?>
    <div class="container container-fluid">
        <ul class="breadcrumbs">
            <li><a href="/<?php echo e($lang); ?>/admin/role/index"><?php echo e(__('admin.pages.roles.title')); ?></a>
            </li>
            <li class="active"><?php echo e($item->name); ?></li>
        </ul>
        <?php echo $__env->make('admin.v2.partials.components.warning', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('admin.v2.partials.components.errors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <form action="/<?php echo e($lang); ?>/admin/role/<?php echo e($item->id); ?>" method="post"
              enctype="multipart/form-data">
            <?php echo e(csrf_field()); ?>

            <div class="block">
                <h2 class="title-secondary"><?php echo e(__('admin.pages.role.title')); ?></h2>
                <div class="input-group <?php echo e($errors->has('name') ? ' has-error' : ''); ?>">
                    <label class="input-group__title"><?php echo e(__('admin.pages.role.name')); ?> *</label>
                    <input type="text" name="name" value="<?php echo e($item->name); ?>"
                           placeholder="<?php echo e(__('admin.labels.fill_field',['field' => __('admin.pages.role.name')])); ?>"
                           class="input-regular" required>
                    <?php if($errors->has('name')): ?>
                        <span class="help-block"><strong><?php echo e($errors->first('name')); ?></strong></span>
                    <?php endif; ?>
                </div>
                <div class="input-group <?php echo e($errors->has('slug') ? ' has-error' : ''); ?>">
                    <label class="input-group__title"><?php echo e(__('admin.pages.role.slug')); ?> *</label>
                    <input type="text" name="slug" value="<?php echo e($item->slug); ?>"
                           placeholder="<?php echo e(__('admin.labels.fill_field',['field' => __('admin.pages.role.slug')])); ?>"
                           class="input-regular" required>
                    <?php if($errors->has('slug')): ?>
                        <span class="help-block"><strong><?php echo e($errors->first('slug')); ?></strong></span>
                    <?php endif; ?>
                </div>
                <div class="input-group <?php echo e($errors->has('description') ? 'has-error' : ''); ?>">
                    <label for="description"
                           class="input-group__title"><?php echo e(__('admin.pages.role.description')); ?></label>
                    <textarea name="description"
                              placeholder="<?php echo e(__('admin.labels.fill_field',['field' => __('admin.pages.role.description')])); ?>"
                              class="input-regular"><?php echo e($item->description); ?></textarea>
                    <?php if($errors->has('description')): ?>
                        <span class="help-block"><strong><?php echo e($errors->first('description')); ?></strong></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="block">
                <h2 class="title-secondary"><?php echo e(__('admin.pages.role.permissions')); ?></h2>
                <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="input-group">
                        <label class="checkbox">
                            <input name="permissions[]" value="<?php echo e($permission->id); ?>" type="checkbox"
                                   <?php if($item->permissions->contains($permission->id)): ?> checked="checked" <?php endif; ?>>
                            <span><?php echo e($permission->name); ?></span>
                        </label>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="block">
                <div class="buttons">
                    <div>
                        <button type="submit" class="btn btn--green"><?php echo e(__('admin.labels.save')); ?></button>
                    </div>
                    <div>
                        <?php if($item->id != 1 and $item->id != 3 and $item->id != 4): ?>
                            <a href="/<?php echo e($lang); ?>/admin/role/<?php echo e($item->id); ?>" class="btn btn--red btn--delete">
                                <?php echo e(__('admin.pages.deleting.submit')); ?>

                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php echo $__env->make('admin.v2.partials.modals.form_delete', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.v2.layout.default.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\OpenServer\domains\enbek\resources\views/admin/v2/pages/roles/edit.blade.php ENDPATH**/ ?>