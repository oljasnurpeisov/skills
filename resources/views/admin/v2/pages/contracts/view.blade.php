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

        <div class="alert alert-info col-sm-12" id="signResult" style="display: none"></div>

        <div style="display: none">
            <form>
                <textarea name="xmlRequest" id="xmlRequest" class="form-control col-sm-12" rows="10"></textarea>
                <textarea name="xmlResponse" id="xmlResponse" class="form-control col-sm-12" rows="10"></textarea>
            </form>
        </div>

        <div class="block">
            @if (!empty($contract))

                @if (pathinfo($contract->link)['extension'] === 'pdf')
                    <object data="{{ route('admin.contracts.get_contract_pdf', ['lang' => 'ru', 'contract_id' => $contract->id]) }}" type="application/pdf" internalinstanceid="3" title="" width="100%" height="600"></object>
                @else
                    <iframe src="{{ route('admin.contracts.get_contract_html', ['lang' => 'ru', 'contract_id' => $contract->id]) }}" frameborder="0" width="100%" height="600"></iframe>
                @endif

                {{--Управление для администрации по текущему маршруту--}}
                @if ($contract->isPending() && $contract->current_route->role_id === Auth::user()->role->role_id)

                    @if ($contract->isQuota())
                    <button
                        data-source="{{ route('admin.contracts.contract.xml', ['lang' => $lang, 'contract_id' => $contract->id]) }}"
                        data-target="{{ route('admin.contracts.contract.next', ['lang' => $lang, 'contract_id' => $contract->id]) }}"
                        class="btn" id="signButton" disabled>Подписать</button>
                    @else
                        <a href="{{ route('admin.contracts.contract.next', ['lang' => $lang, 'contract_id' => $contract->id]) }}"
                            class="btn">Одобрить</a>
                    @endif

                    @if (!Auth::user()->hasRole('moderator'))
                        <a href="" class="btn btn--red" id="rejectBtn" data-btn="Вернуть" data-title="Укажите причину отправки на доработку" data-action="{{ route('admin.contracts.contract.reject_by_admin', ['lang' => $lang, 'contract_id' => $contract->id]) }}">Отправить на доработку</a>
                    @else
                        <a href="" class="btn btn--red" id="rejectBtn" data-btn="Отклонить договор" data-title="Укажите причину расторжения договора" data-action="{{ route('admin.contracts.contract.reject_by_moderator', ['lang' => $lang, 'contract_id' => $contract->id]) }}">Отклонение договора</a>
                    @endif
                @endif

                {{--Расторжение договора для директора--}}
                @if ($contract->isSigned() and Auth::user()->hasRole('rukovoditel'))
                    <a href="" class="btn btn--red" id="rejectBtn" data-btn="Расторгнуть" data-title="Укажите причину расторжения договора" data-action="{{ route('admin.contracts.reject_contract', ['lang' => $lang, 'contract_id' => $contract->id]) }}">Расторгнуть договор</a>
                @endif

                {{--Если договор отклонен администрацией - блок для модератора--}}
                @if ($contract->isRejectedByAdmin() and Auth::user()->hasRole('moderator'))
                    <a href="" class="btn btn--red" id="rejectBtn" data-btn="Расторгнуть" data-title="Укажите причину расторжения договора" data-action="{{ route('admin.contracts.contract.reject_by_moderator', ['lang' => $lang, 'contract_id' => $contract->id]) }}">Отклонение договора</a>
                    <a href="{{ route('admin.contracts.contract.reject_by_admin_cancel', ['lang' => $lang, 'contract_id' => $contract->id]) }}" class="btn" id="rejectBtn">Отмена отклонения</a>
                @endif
            @else
                {{--Предпросмотр договора--}}
                <iframe src="{{ route('admin.contracts.get_contract_html_preview', ['lang' => 'ru', 'course_id' => $course->id, 'type' => $type]) }}" frameborder="0" width="100%" height="600"></iframe>
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

        const layer = {
            host: '127.0.0.1',
            port: 13579
        };

        let kalkan = null;
        let runner = null;
        let storagePath = 'PKCS12'; // Default storage
        let source;
        let target;

        $(document).ready(function () {
            if ($('#signButton').length > 0) {
                kalkan = new Kalkan(layer.host, layer.port, true, LayerIsReady, LayerError);
            }
        });

        $('#signButton').click(function() {

            $(this).attr('disabled', 'disabled');

            source = $(this).data('source');
            target = $(this).data('target');

            $.getJSON(source, function(response) {
                if(response.xml) {
                    $('#xmlRequest').val(response.xml);
                    chooseStoragePath(storagePath);
                }
            });
        });

        /**
         * Layer is ready constructor
         */
        function LayerIsReady() {
            runner = 'NCALayer';
            AppletIsReady();
        }

        /**
         * Applet is ready constructor
         */
        function AppletIsReady() {
            $('#signButton').removeAttr('disabled');
        }

        /**
         * Layer error fallback function
         * Try to run applet if no NCALayer present
         */
        function LayerError() {
            updateAjaxResult('Для подписания документа запустите приложение NCALayer', 'danger');
        }

        /**
         * Choose storage path
         *
         * @param storage
         * @returns {boolean}
         */
        function chooseStoragePath(storage) {
            storagePath = storage;
            signXml(function (result) {
                sendSignature(result);
            });
        }

        /**
         * Write message to result container
         * @param message
         * @param type
         */
        function updateAjaxResult(message, type) {
            if (message === '') {
                $('#signResult').html(message).hide();
            } else {
                $('#signResult').html(message).removeClass('alert-danger alert-info alert-success').addClass(type ? ('alert-' + type) :  'alert-info').show();
            }
        }

        /**
         * Send signed document to server
         */
        function sendSignature(request)
        {
            $.post(target, {"_token": "{{ csrf_token() }}", xml: request})
                .done(function(response) {
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    } else if(response.message) {
                        if (response.success) {
                            updateAjaxResult(response.message, 'success');
                        } else {
                            updateAjaxResult(response.message, 'danger');
                        }
                    } else {
                        updateAjaxResult('Неизвестная ошибка. Попробуйте позже.', 'danger');
                    }
                })
                .fail(function(result) {
                    if(result.responseJSON && result.responseJSON.message) {
                        updateAjaxResult(result.responseJSON.message, 'danger');
                    } else {
                        updateAjaxResult('Неизвестная ошибка. Попробуйте позже.', 'danger');
                    }
                })
                .always(function() {
                    $('#signButton').removeAttr('disabled');
                });
        }

        /**
         * Run XML signing process
         * @param callback
         */
        function signXml(callback) {

            updateAjaxResult('Подписание...', 'info');

            let data = document.getElementById("xmlRequest").value;

            if (data !== "") {
                kalkan.authXml(storagePath, 'SIGNATURE', data, function (rw) {
                    if (rw.responseObject) {
                        document.getElementById("xmlResponse").value = rw.responseObject;
                        callback(document.getElementById("xmlResponse").value);
                    } else {

                        $('#signButton').removeAttr('disabled');

                        if (rw.getErrorCode() === "WRONG_PASSWORD") {
                            updateAjaxResult('Указан неверный пароль', 'warning');
                        } else {

                            document.getElementById("xmlResponse").value = "";

                            if (rw.message) {
                                switch (rw.message) {
                                    case 'storage.empty':
                                        updateAjaxResult('Выбранное устройство недоступно', 'warning');
                                        break;
                                    case 'action.canceled':
                                        updateAjaxResult('');
                                        break;
                                }
                            }
                        }
                    }
                });
            } else {
                updateAjaxResult('Данные не указаны', 'danger');
            }
        }

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
