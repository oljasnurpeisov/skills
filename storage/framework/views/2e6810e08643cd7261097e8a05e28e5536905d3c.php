<div id="dialog-confirm-delete" class="modal" style="display: none;">
    <h4 class="title-secondary"><?php echo e(__('admin.pages.deleting.title')); ?></h4>
    <div class="plain-text">
        <p><?php echo e(__('admin.pages.deleting.hint')); ?></p>
    </div>
    <hr>
    <form id="form-delete" method="POST">
        <?php echo e(csrf_field()); ?>

        <?php echo e(method_field('DELETE')); ?>

        <div class="buttons justify-end">
            <div>
                <button type="submit" class="btn btn--red"><?php echo e(__('admin.pages.deleting.submit')); ?></button>
            </div>
            <div>
                <button class="btn" data-fancybox-close><?php echo e(__('admin.labels.cancel')); ?></button>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    $(".icon-delete, .btn--delete").click(function (e) {
        e.preventDefault();
        var link = $(this).attr("href");
        if (link !== undefined) {
            $("#form-delete").attr("action", link);

            $.fancybox.open({
                src: "#dialog-confirm-delete",
                touch: false
            });
        }
    });
</script>
<?php /**PATH D:\OpenServer\domains\enbek\resources\views/admin/v2/partials/modals/form_delete.blade.php ENDPATH**/ ?>