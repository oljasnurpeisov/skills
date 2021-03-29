<div id="dialog-confirm-delete" class="modal" style="display: none;">
    <h4 class="title-secondary">{{ __('admin.pages.deleting.title') }}</h4>
    <div class="plain-text">
        <p>{{ __('admin.pages.deleting.hint') }}</p>
    </div>
    <hr>
    <form id="form-delete" method="POST">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
        <div class="buttons justify-end">
            <div>
                <button type="submit" class="btn btn--red">{{ __('admin.pages.deleting.submit') }}</button>
            </div>
            <div>
                <button class="btn" data-fancybox-close>{{ __('admin.labels.cancel') }}</button>
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
