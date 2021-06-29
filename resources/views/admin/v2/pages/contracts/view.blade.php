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
            @if (!empty($contract))
                <iframe src="{{ route('admin.contracts.get_contract_html', ['lang' => 'ru', 'contract_id' => $contract->id]) }}" frameborder="0" width="100%" height="600"></iframe>

                @if ($contract->isPending() && $contract->current_route->role_id === Auth::user()->role->role_id)
                    <a href="{{ route('admin.contracts.contract.next', ['lang' => $lang, 'contract_id' => $contract->id]) }}" class="btn">Подписать</a>
                @endif
            @else
                <iframe src="{{ route('admin.contracts.get_contract_html_preview', ['lang' => 'ru', 'course_id' => $course->id, 'type' => $type]) }}" frameborder="0" width="100%" height="600"></iframe>
            @endif

            @if ($contract->isSigned() and $contract->isQuota() and Auth::user()->hasRole('admin'))
                <a href="" class="btn btn--red" id="rejectBtn">Расторгнуть договор</a>
            @endif
        </div>
    </div>

    <div id="dialog-confirm-reject" class="modal" style="display: none;">
        <h4 class="title-secondary">Укажите причину расторжения договора</h4>
        <hr>
        <form action="{{ route('admin.contracts.reject_contract', ['lang' => $lang, 'contract_id' => $contract->id]) }}" method="POST">
            @csrf
            <div class="input-group" id="rejectMessageBlock">
                <textarea required name="message" id="message" placeholder="Сообщение о расторжении договора" class="input-regular"></textarea>
            </div>
            <div class="buttons justify-end">
                <div>
                    <button class="btn btn--red">Расторгнуть</button>
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
