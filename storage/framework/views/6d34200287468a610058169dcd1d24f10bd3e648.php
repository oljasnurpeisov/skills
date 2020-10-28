<?php $__env->startSection('title',__('admin.pages.roles.title').' | '.__('default.site_name')); ?>

<?php $__env->startSection('head'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container container-fluid">
        <div class="title-block">
            <div class="row row--multiline align-items-center">
                <div class="col-md-4">
                    <h1 class="title-primary" style="margin-bottom: 0"><?php echo e(__('admin.pages.roles.title')); ?></h1>
                </div>
                <div class="col-md-8 text-right-md text-right-lg">
                    <div class="flex-form">
                        <div>
                            <form action="/<?php echo e($lang); ?>/admin/role/index" method="get" class="input-button">
                                <input type="text" name="term"
                                       placeholder="<?php echo e(__('admin.pages.role.name')); ?>"
                                       class="input-regular input-regular--solid" style="width: 282px;"
                                       value="<?php echo e($term); ?>">
                                <button class="btn btn--green"><?php echo e(__('admin.labels.search')); ?></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php echo $__env->make('admin.v2.partials.components.warning', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="block">
            <h2 class="title-secondary"><?php echo e($term ? __('admin.labels.search_result') : __('admin.labels.record_list')); ?></h2>

            <?php if(count($items) > 0): ?>
                <table class="table records">
                    <colgroup>
                        <col span="1" style="width: 5%;">
                        <col span="1" style="width: 20%;">
                        <col span="1" style="width: 7%;">
                        <col span="1" style="width: 7%;">
                        <col span="1" style="width: 7%;">
                        <col span="1" style="width: 15%;">
                        <col span="1" style="width: 15%;">
                        <col span="1" style="width: 8%;">
                        <col span="1" style="width: 8%;">
                        <col span="1" style="width: 8%;">
                    </colgroup>
                    <thead>
                    <tr>
                        <th>#</th>
                        <th><?php echo e(__('admin.pages.role.name')); ?></th>
                        <th><?php echo e(__('admin.pages.role.slug')); ?></th>
                        <th><?php echo e(__('admin.pages.role.description')); ?></th>
                        <th><?php echo e(__('admin.labels.created_at')); ?></th>
                        <th><?php echo e(__('admin.labels.updated_at')); ?></th>
                        <th><?php echo e(__('admin.labels.actions')); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($item->id); ?></td>
                            <td><?php echo e($item->name); ?></td>
                            <td><?php echo e($item->slug); ?></td>
                            <td><?php echo e($item->description); ?></td>
                            <td><?php echo $item->created_at; ?></td>
                            <td><?php echo $item->updated_at; ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="/<?php echo e($lang); ?>/admin/role/<?php echo e($item->id); ?>" title="<?php echo e(__('admin.labels.edit')); ?>"
                                       class="icon-btn icon-btn--yellow icon-edit"></a>
                                    <?php if($item->id != 1 and $item->id != 3 and $item->id != 4): ?>
                                        <a href="/<?php echo e($lang); ?>/admin/role/<?php echo e($item->id); ?>"
                                           title="<?php echo e(__('admin.pages.deleting.submit')); ?>"
                                           class="icon-btn icon-btn--pink icon-delete"></a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <div class="text-right">
                <?php echo e($items->appends(['term' => $term])->links('vendor.pagination.bootstrap')); ?>

            </div>
        </div>
    </div>

    <?php echo $__env->make('admin.v2.partials.modals.form_delete', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.v2.layout.default.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\OpenServer\domains\enbek\resources\views/admin/v2/pages/roles/index.blade.php ENDPATH**/ ?>