<?php $__env->startSection('title',$item->surname.' '.$item->name.' '.$item->middle_name.' | '.__('admin.site_name')); ?>

<?php $__env->startSection('content'); ?>
    <div class="container container-fluid">
        <ul class="breadcrumbs">
            <li><a href="/<?php echo e($lang); ?>/admin/user/index"><?php echo e(__('admin.pages.users.title')); ?></a></li>
            <li class="active"><?php echo e($item->surname.' '.$item->name.' '.$item->middle_name); ?></li>
        </ul>
        <?php echo $__env->make('admin.v2.partials.components.warning', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php if(session('newPassword')): ?>
            <div class="alert alert-warning" role="alert">
                <strong><?php echo e(__('admin.notifications.new_password',['password' => session('newPassword')])); ?></strong>
            </div>
        <?php endif; ?>
        <?php echo $__env->make('admin.v2.partials.components.errors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <form action="/<?php echo e($lang); ?>/admin/user/<?php echo e($item->id); ?>" method="post"
              enctype="multipart/form-data">
            <?php echo e(csrf_field()); ?>

            <div class="block">
                <h2 class="title-secondary"><?php echo e(__('admin.pages.user.title')); ?></h2>
                <div class="input-group <?php echo e($errors->has('full_name') ? ' has-error' : ''); ?>">
                    <label class="input-group__title"><?php echo e(__('admin.pages.user.full_name')); ?> *</label>
                    <input type="text" name="name" value="<?php echo e($item->name); ?>"
                           placeholder="<?php echo e(__('admin.labels.fill_field',['field' => __('admin.pages.user.full_name')])); ?>"
                           class="input-regular" required>
                    <?php if($errors->has('full_name')): ?>
                        <span class="help-block"><strong><?php echo e($errors->first('full_name')); ?></strong></span>
                    <?php endif; ?>
                </div>
                <div class="input-group <?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
                    <label class="input-group__title"><?php echo e(__('admin.pages.user.email')); ?> *</label>
                    <input type="email" name="email" value="<?php echo e($item->email); ?>"
                           placeholder="<?php echo e(__('admin.labels.fill_field',['field' => __('admin.pages.user.email')])); ?>"
                           class="input-regular" required>
                    <?php if($errors->has('email')): ?>
                        <span class="help-block"><strong><?php echo e($errors->first('email')); ?></strong></span>
                    <?php endif; ?>
                </div>
                <div class="input-group <?php echo e($errors->has('iin') ? ' has-error' : ''); ?>">
                    <label class="input-group__title"><?php echo e(__('admin.pages.user.iin_bin')); ?> *</label>
                    <input type="text" name="iin" value="<?php echo e($item->iin); ?>"
                           placeholder="<?php echo e(__('admin.labels.fill_field',['field' => __('admin.pages.user.iin_bin')])); ?>"
                           class="input-regular" required>
                    <?php if($errors->has('iin')): ?>
                        <span class="help-block"><strong><?php echo e($errors->first('iin')); ?></strong></span>
                    <?php endif; ?>
                </div>
                <div class="input-group <?php echo e($errors->has('type_of_ownership') ? ' has-error' : ''); ?>">
                    <label class="input-group__title"><?php echo e(__('admin.pages.user.type_of_ownership')); ?> *</label>
                    <select name="type_of_ownership" id="type_of_ownership" class="input-regular chosen"
                            data-placeholder=" " required>
                        <?php $__currentLoopData = $types_of_ownership; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($type->id); ?>"
                                    <?php if($type->id==$item->type_ownership->id): ?> selected='selected' <?php endif; ?> ><?php echo e($type->getAttribute('name_'.$lang) ??  $type->getAttribute('name_ru')); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php if($errors->has('type_of_ownership')): ?>
                        <span class="help-block"><strong><?php echo e($errors->first('type_of_ownership')); ?></strong></span>
                    <?php endif; ?>
                </div>
                <div class="input-group <?php echo e($errors->has('iin') ? ' has-error' : ''); ?>">
                    <label class="input-group__title"><?php echo e(__('admin.pages.user.company_name')); ?> *</label>
                    <input type="text" name="company_name" value="<?php echo e($item->company_name); ?>"
                           placeholder="<?php echo e(__('admin.labels.fill_field',['field' => __('admin.pages.user.company_name')])); ?>"
                           class="input-regular" required>
                    <?php if($errors->has('iin')): ?>
                        <span class="help-block"><strong><?php echo e($errors->first('company_name')); ?></strong></span>
                    <?php endif; ?>
                </div>
                <div class="input-group <?php echo e($errors->has('company_logo') ? ' has-error' : ''); ?>">
                    <label class="input-group__title"><?php echo e(__('admin.pages.user.company_logo')); ?></label>
                    <div class="row">
                        <div class="col-md-2">
                            <img src="<?php echo e($item->getAvatar()); ?>" id="avatar_image" class="file-upload-image"
                                 style="height: 150px">
                        </div>
                        <div class="col-md-10">
                            <input type="hidden" name="company_logo" value="<?php echo e($item->getAvatar()); ?>">
                            <div id="avatar" class="file-upload">
                                <div id="avatar_uploader" class="file">
                                    <div class="progress">
                                        <div class="progress-bar"></div>
                                    </div>
                                    <span class="file__name">
                                    .png, .jpg • 1 MB<br/>
                                    <strong><?php echo e(__('admin.labels.upload_image')); ?></strong>
                                </span>
                                </div>
                            </div>
                            <?php if($errors->has('company_logo')): ?>
                                <span class="help-block"><strong><?php echo e($errors->first('company_logo')); ?></strong></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block">
                <h2 class="title-secondary" style="margin-bottom: .3125em;"><?php echo e(__('admin.pages.user.role')); ?> *</h2>
                <div class="input-group <?php echo e($errors->has('role_id') ? ' has-error' : ''); ?>">
                    <select name="role_id" id="role_id" class="input-regular chosen" data-placeholder=" " required>
                        
                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($role->id); ?>"
                                    <?php if($item->hasRole($role->slug)): ?> selected <?php endif; ?>><?php echo e($role->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php if($errors->has('role_id')): ?>
                        <span class="help-block"><strong><?php echo e($errors->first('role_id')); ?></strong></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="block">
                <h2 class="title-secondary" style="margin-bottom: .3125em;"><?php echo e(__('admin.pages.user.status')); ?> *</h2>
                <div class="input-group <?php echo e($errors->has('role_id') ? ' has-error' : ''); ?>">
                    <select name="is_activate" id="is_activate" class="input-regular chosen" data-placeholder="" required>
                        <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($status); ?>"
                                    <?php if($item->is_activate == $status): ?> selected <?php endif; ?>><?php echo e(__("admin.pages.user.statuses.$status")); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php if($errors->has('is_activate')): ?>
                        <span class="help-block"><strong><?php echo e($errors->first('is_activate')); ?></strong></span>
                    <?php endif; ?>
                </div>
                <div class="input-group" id="rejectMessageBlock" style='display:none;'>
                    <label class="input-group__title">Причина отказа*</label>
                    <textarea name="rejectMessage" id="rejectMessage" placeholder="Заполните поле: Причина отказа"
                              class="input-regular"></textarea>
                </div>
            </div>
            <div class="block">
                <div class="buttons">
                    <div>
                        <button type="submit" class="btn btn--green"><?php echo e(__('admin.labels.save')); ?></button>
                    </div>
                    <div>
                        <a href="/<?php echo e($lang); ?>/admin/user/<?php echo e($item->id); ?>/passwordUpdate" class="btn btn--blue">
                            <?php echo e(__('admin.labels.update_password')); ?>

                        </a>
                    </div>
                    <div>
                        <a href="/<?php echo e($lang); ?>/admin/user/<?php echo e($item->id); ?>" class="btn btn--red btn--delete">
                            <?php echo e(__('admin.pages.deleting.submit')); ?>

                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php echo $__env->make('admin.v2.partials.modals.form_delete', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        var uploaders = new Array();

        initUploaders = function (uploaders) {
            $(".file-upload").each(function () {
                var el = $(this),
                    button = el.attr("id") + "_uploader",
                    progressBar = el.find('.progress-bar'),
                    input = el.siblings('input'),
                    fileUploadPlaceholder = $("#" + el.attr("id") + "_image");

                var uploader = new plupload.Uploader({
                    runtimes: 'gears,html5,flash,silverlight,browserplus',
                    browse_button: button,
                    drop_element: button,
                    max_file_size: '1mb',
                    url: "/ajaxUploadImage?_token=<?php echo e(csrf_token()); ?>",
                    flash_swf_url: '/assets/admin/libs/plupload/js/Moxie.swf',
                    silverlight_xap_url: '/assets/admin/libs/plupload/js/Moxie.xap',
                    filters: [
                        {title: "Image files", extensions: "png,jpg,jpeg"}
                    ],
                    unique_names: true,
                    multiple_queues: false,
                    multi_selection: false
                });

                uploader.bind('FilesAdded', function (up, files) {
                    progressBar.css({width: 0});
                    el.removeClass('error').removeClass('success').addClass('disabled');
                    uploader.start();
                });

                uploader.bind("UploadProgress", function (up, file) {
                    progressBar.css({width: file.percent + "%"});
                });

                uploader.bind("FileUploaded", function (up, file, response) {
                    var obj = $.parseJSON(response.response.replace(/^.*?({.*}).*?$/gi, "$1"));
                    input.val(obj.location);
                    fileUploadPlaceholder.attr('src', obj.location);
                    el.removeClass('disabled').removeClass('error').addClass('success');
                    up.refresh();
                });

                uploader.bind("Error", function (up, err) {
                    progressBar.css({width: 0});
                    el.removeClass('disabled').removeClass('success').addClass('error');
                    up.refresh();
                });

                uploader.init();

                uploaders.push(uploader);
            });
        };

        initUploaders(uploaders);

        $(document).ready(function(){
            $('#is_activate').on('change', function() {
                if ( this.value == '2')
                {
                    $("#rejectMessageBlock").show();
                    $("#rejectMessage").attr('required', '');
                }
                else
                {
                    $("#rejectMessageBlock").hide();
                    $("#rejectMessage").removeAttr('required');
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.v2.layout.default.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\OpenServer\domains\enbek\resources\views/admin/v2/pages/users/edit.blade.php ENDPATH**/ ?>