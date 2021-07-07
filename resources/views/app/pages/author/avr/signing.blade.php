@extends('app.layout.default.template')

@section('content')
    <main class="main">
        <section class="plain">
            <div class="container">
                <div class="row row--multiline column-reverse-sm">
                    <div class="col-md-12">
                        <iframe src="{{ route('author.avr.signing.avrDoc', ['lang' => 'ru', 'avr_id' => $avr->id]) }}" frameborder="0" width="100%" height="600"></iframe>

                        @if ($avr->isSignator())

                            <div class="alert alert-info col-sm-12" id="signResult" style="display: none"></div>

                            <div style="display: none">
                                <form>
                                    <textarea name="xmlRequest" id="xmlRequest" class="form-control col-sm-12" rows="10"></textarea>
                                    <textarea name="xmlResponse" id="xmlResponse" class="form-control col-sm-12" rows="10"></textarea>
                                </form>
                            </div>

                            @if (empty($avr->invoice_link))
                                <form action="{{ route('author.avr.signing.update', ['lang' => 'ru', 'avr_id' => $avr->id]) }}">
                                    <table>
                                        <tr>
                                            <td>Номер АВР:</td>
                                            <td><input type="text" name="avr_number" class="input-regular" placeholder="Номер АВР" required></td>
                                        </tr>
                                        <tr>
                                            <td>Счет фактуры:</td>
{{--                                            <td><input type="file" name="invoice" placeholder="Счет фактуры" required></td>--}}
                                            <td>
                                                <div data-url="{{ route('ajaxUploadFile') }}" data-maxfiles="1"
                                                     data-maxsize="1" data-acceptedfiles="application/pdf"
                                                     class="dropzone-default dropzone-multiple">
                                                    <input type="text" name="invoice" value="" required>
                                                    <div class="dropzone-default__info">JPG, PNG, PDF • {{__('default.pages.profile.max_file_title') }}. 1MB</div>
                                                    <a href="javascript:;" title="{{__('default.pages.profile.choose_file')}}" class="dropzone-default__link">{{__('default.pages.profile.choose_file')}}</a>
                                                    <div class="previews-container"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td><button class="btn">Сохранить</button></td>
                                        </tr>
                                    </table>
                                </form>
                            @else
                                <button
                                    data-source="{{ route('author.avr.signing.xml', ['lang' => $lang, 'avr_id' => $avr->id]) }}"
                                    data-target="{{ route('author.avr.signing.next', ['lang' => $lang, 'contract_id' => $contract->id]) }}"
                                    class="btn btn-success" id="signButton" disabled>Подписать
                                </button>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection


<style>
    #contract * {word-break: break-word}
    #contract table {max-width: 100% !important;}
    #contract tr {max-width: 50% !important;}
</style>
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
    </script>
@endsection

