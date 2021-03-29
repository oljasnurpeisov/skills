<div id="dialog-confirm-reject" class="modal" style="display: none;">
    <h4 class="title-secondary">{{ __('admin.pages.reject.title') }}</h4>
    <hr>
    <form action="/{{$lang}}/admin/author/{{ $item->id }}" id="form-reject" method="POST">
        {{ csrf_field() }}
        {{--{{ method_field('DELETE') }}--}}
        <div class="input-group" id="rejectMessageBlock">
            <label class="input-group__title">{{ __('admin.pages.reject.hint') }}</label>
            <textarea name="rejectMessage" id="rejectMessage" placeholder=""
                      class="input-regular"></textarea>
        </div>
        <div class="buttons justify-end">
            <div>
                {{--<button type="submit" name="action" value="reject" class="btn btn--red">{{ __('admin.pages.deleting.submit') }}</button>--}}
            </div>
            <div>
                <button class="btn" data-fancybox-close>{{ __('admin.labels.cancel') }}</button>
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
