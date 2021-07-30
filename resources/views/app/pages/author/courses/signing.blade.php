@extends('app.layout.default.template')

@section('content')
    <style>
        #contract * {
            word-break: break-word
        }

        #contract table {
            max-width: 100% !important;
        }

        #contract tr {
            max-width: 50% !important;
        }
    </style>
    <main class="main">
        <section class="plain">
            <div class="container">
                <div class="title-block">
                    <div class="row row--multiline align-items-center">
                        <div class="col-auto"><h1
                                class="title-primary">{{__('default.pages.courses.my_courses_title')}}</h1></div>
                        <div class="col-auto">
                            <a href="/{{$lang}}/create-course">
                                <div class="ghost-btn ghost-btn--blue">{{__('default.pages.courses.create_course')}}
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="mobile-dropdown">
                    <div class="mobile-dropdown__title dynamic">{{__('default.pages.courses.my_courses')}}</div>
                    <div class="mobile-dropdown__desc">
                        <ul class="tabs-links">
                            <li @if(Route::currentRouteName() === 'author.courses.my_courses') class="active" @endif>
                                <a href="{{ route('author.courses.my_courses', ['lang' => $lang]) }}" title="{{__('default.pages.courses.my_courses')}}">{{__('default.pages.courses.my_courses')}}</a>
                            </li>
                            <li @if(Route::currentRouteName() === 'author.courses.drafts') class="active" @endif>
                                <a href="{{ route('author.courses.drafts', ['lang' => $lang]) }}" title="{{__('default.pages.courses.drafts')}}">{{__('default.pages.courses.drafts')}}</a>
                            </li>
                            <li @if(Route::currentRouteName() === 'author.courses.on_check') class="active" @endif>
                                <a href="{{ route('author.courses.on_check', ['lang' => $lang]) }}" title="{{__('default.pages.courses.my_courses_onCheck')}}">{{__('default.pages.courses.my_courses_onCheck')}}</a>
                            </li>
                            <li @if(Route::currentRouteName() === 'author.courses.signing' || Route::currentRouteName() === 'author.courses.signing.contract') class="active" @endif>
                                <a href="{{ route('author.courses.signing', ['lang' => $lang]) }}" title="{{ __('default.pages.courses.my_courses_signed') }}">{{ __('default.pages.courses.my_courses_signed') }}</a>
                            </li>
                            <li @if(Route::currentRouteName() === 'author.courses.unpublished') class="active" @endif>
                                <a href="{{ route('author.courses.unpublished', ['lang' => $lang]) }}" title="{{__('default.pages.courses.my_courses_unpublished')}}">{{__('default.pages.courses.my_courses_unpublished')}}</a>
                            </li>
                            <li @if(Route::currentRouteName() === 'author.courses.deleted') class="active" @endif>
                                <a href="{{ route('author.courses.deleted', ['lang' => $lang]) }}" title="{{__('default.pages.courses.my_courses_deleted')}}">{{__('default.pages.courses.my_courses_deleted')}}</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="alert alert-info col-sm-12" id="signResult" style="display: none"></div>

                <div style="display: none">
                    <form>
                        <textarea name="xmlRequest" id="xmlRequest" class="form-control col-sm-12" rows="10"></textarea>
                        <textarea name="xmlResponse" id="xmlResponse" class="form-control col-sm-12" rows="10"></textarea>
                    </form>
                </div>

                <div class="row row--multiline column-reverse-sm">
                    <div class="col-md-12">
                        {{--                        <div id="contract" style="max-width: 100%">--}}
                        {{--                            {!! $contract !!}--}}
                        {{--                        </div>--}}
                        <iframe
                            src="{{ route('author.courses.signing.contractDoc', ['lang' => 'ru', 'contract_id' => $contract->id]) }}"
                            frameborder="0" width="100%" height="600"></iframe>

                        <a href="{{ route('author.courses.signing.contract.reject', ['lang' => $lang, 'contract_id' => $contract->id]) }}"
                           class="btn btn-danger" style="margin-right: 15px;" id="declineButton">{{ __('default.pages.courses.reject') }}</a>
                        @if ($contract->isQuota())
                            <button
                                data-source="{{ route('author.courses.signing.contract.xml', ['lang' => $lang, 'contract_id' => $contract->id]) }}"
                                data-target="{{ route('author.courses.signing.contract.next', ['lang' => $lang, 'contract_id' => $contract->id]) }}"
                                class="btn btn-success" id="signButton" disabled>{{ __('default.pages.courses.sign') }}
                            </button>
                        @else
                            <a href="{{ route('author.courses.signing.contract.next', ['lang' => $lang, 'contract_id' => $contract->id]) }}"
                               class="btn btn-success">{{ __('default.pages.courses.accept') }}</a>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@section('scripts')
    <!--Only this page's scripts-->
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
            $('#declineButton').attr('disabled', 'disabled');

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
                    $('#declineButton').removeAttr('disabled');
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
                        $('#declineButton').removeAttr('disabled');

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
    </script>
@endsection


