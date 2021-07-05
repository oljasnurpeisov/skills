@extends('admin.v2.layout.default.template')

@section('title', $title .' | '.__('admin.site_name'))

@section('head')

@endsection

@section('content')
    <div class="container container-fluid">
        <div class="title-block">
            <div class="row row--multiline align-items-center">
                <div class="col-md-4">
                    <h1 class="title-primary" style="margin-bottom: 0">{{ $title }}</h1>
                </div>
            </div>
        </div>

        @include('admin.v2.partials.components.warning')

        <div class="block">
            <iframe src="{{ route('admin.avr.get_contract_html', ['lang' => 'ru', 'avr_id' => $avr->id]) }}" frameborder="0" width="100%" height="600"></iframe>

            {{--Управление для администрации по текущему маршруту--}}
            @if ($avr->isPending() && $avr->isSignator())
                <a href="{{ route('admin.avr.next', ['lang' => $lang, 'avr_id' => $avr->id]) }}" class="btn">Подписать</a>
            @endif
        </div>
    </div>

    <div id="dialog-confirm-reject" class="modal" style="display: none;">
        <h4 class="title-secondary" id="reject-title"></h4>
        <hr>
        <form  method="POST">
            @csrf
            <div class="input-group" id="rejectMessageBlock">
                <textarea required name="message" id="message" placeholder="Сообщение о расторжении договора" class="input-regular"></textarea>
            </div>
            <div class="buttons justify-end">
                <div>
                    <button class="btn btn--red" id="reject-btn"></button>
                </div>
                <div>
                    <button class="btn" data-fancybox-close="">Отмена</button>
                </div>
            </div>
        </form>
    </div>

    @include('admin.v2.partials.modals.form_delete')
@endsection

@section('scripts')
    <script>
        $("#rejectBtn").click(function (e) {
            e.preventDefault();

            $('#dialog-confirm-reject form').attr('action', $(this).attr('data-action'));
            $('#reject-title').text($(this).attr('data-title'));
            $('#reject-btn').text($(this).attr('data-btn'));

            var link = $(this).attr("href");
            if (link !== undefined) {
                $("#author_form").attr("action", link);

                $.fancybox.open({
                    src: "#dialog-confirm-reject",
                    touch: false
                });
            }
        });
    </script>
@endsection
