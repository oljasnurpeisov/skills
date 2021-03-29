<?php $__env->startSection('title',__('admin.pages.authors.title').' | '.__('admin.site_name')); ?>

<?php $__env->startSection('head'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container container-fluid">
        <div class="title-block">
            <div class="row row--multiline align-items-center">
                <div class="col-md-4">
                    <h1 class="title-primary" style="margin-bottom: 0"><?php echo e(__('admin.pages.authors.title')); ?></h1>
                </div>
                <div class="col-md-8 text-right-md text-right-lg">
                    <div class="flex-form">
                        <div>
                            <form action="/<?php echo e($lang); ?>/admin/author/index" method="get" class="input-button">
                                <input type="text" name="term"
                                       placeholder="<?php echo e(__('admin.pages.user.surname').' / '.__('admin.pages.user.name').' / '.__('admin.pages.user.middle_name')); ?>"
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
                        <th><?php echo e(__('admin.pages.user.surname').' '.__('admin.pages.user.name').' '.__('admin.pages.user.middle_name')); ?></th>
                        <th><?php echo e(__('admin.pages.user.email')); ?></th>
                        <th><?php echo e(__('admin.pages.user.role')); ?></th>
                        <th><?php echo e(__('admin.pages.user.status')); ?></th>
                        <th><?php echo e(__('admin.labels.created_at')); ?></th>
                        <th><?php echo e(__('admin.labels.updated_at')); ?></th>
                        <th><?php echo e(__('admin.labels.actions')); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php ($creator = $item->creator); ?>
                        <?php ($modifier = $item->modifier); ?>
                        <?php ($remover = $item->remover); ?>
                        <tr>
                            <td><?php echo e($item->id); ?></td>
                            <td><?php echo e($item->surname.' '.$item->name.' '.$item->middle_name); ?></td>
                            <td><a href="mailto:<?php echo e($item->email); ?>" target="_blank"><?php echo e($item->email); ?></a></td>
                            <td><?php echo e(($item->roles()->first()) ? $item->roles()->first()->name : '-'); ?></td>
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            <td>
                                <?php if($item->is_activate == 0): ?>
                                    <div class="alert alert-warning" style="margin: 0;">
                                        <?php elseif($item->is_activate == 1): ?>
                                            <div class="alert alert-success" style="margin: 0;">
                                                <?php else: ?>
                                                    <div class="alert alert-danger" style="margin: 0;">
                                                        <?php endif; ?>
                                                        <?php echo e(__("admin.pages.user.statuses.$item->is_activate")); ?>

                                                    </div>
                                            </div>
                                    </div>
                            </td>
                            <td><?php echo $item->created_at . ($creator ? '<br>'.($creator->surname.' '.$creator->name.' '.$creator->middle_name) : ''); ?></td>
                            <td><?php echo $item->updated_at . ($modifier ? '<br>'.($modifier->surname.' '.$modifier->name.' '.$modifier->middle_name) : ''); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="/<?php echo e($lang); ?>/admin/author/<?php echo e($item->id); ?>" title="<?php echo e(__('admin.labels.view')); ?>"
                                       class="icon-btn icon-btn--yellow icon-eye"></a>
                                    
                                       
                                    
                                       
                                       
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

<?php echo $__env->make('admin.v2.layout.default.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\OpenServer\domains\enbek\resources\views/admin/v2/pages/authors/faq.blade.php ENDPATH**/ ?>