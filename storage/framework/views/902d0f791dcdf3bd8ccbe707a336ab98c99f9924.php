<div id="dialog-confirm-reject" class="modal" style="display: none;">
    <h4 class="title-secondary"><?php echo e(__('admin.pages.reject.title')); ?></h4>
    <hr>
    <form action="/<?php echo e($lang); ?>/admin/author/<?php echo e($item->id); ?>" id="form-reject" method="POST">
        <?php echo e(csrf_field()); ?>

        
        <div class="input-group" id="rejectMessageBlock">
            <label class="input-group__title"><?php echo e(__('admin.pages.reject.hint')); ?></label>
            <textarea name="rejectMessage" id="rejectMessage" placeholder=""
                      class="input-regular"></textarea>
        </div>
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
    $("#rejectBtn").click(function (e) {
        e.preventDefault();
        var link = $(this).attr("href");
        if (link !== undefined) {
            $("#form-reject").attr("action", link);

            $.fancybox.open({
                src: "#dialog-confirm-reject",
                touch: false
            });
        }
    });
</script>
<?php /**PATH D:\OpenServer\domains\enbek\resources\views/admin/v2/partials/modals/form_reject.blade.php ENDPATH**/ ?>