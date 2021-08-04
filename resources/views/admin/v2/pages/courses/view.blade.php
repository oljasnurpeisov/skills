@extends('admin.v2.layout.default.template')

@section('title',$item->name.' | '.__('admin.site_name'))

@section('content')
    <div class="container container-fluid">
        <ul class="breadcrumbs">
            <li><a href="/{{$lang}}/admin/courses/index">{{ __('admin.pages.courses.title') }}</a></li>
            <li class="active">{{ $item->name }}</li>
        </ul>
        @include('admin.v2.partials.components.warning')
        @if(session('newPassword'))
            <div class="alert alert-warning" role="alert">
                <strong>{{ __('admin.notifications.new_password',['password' => session('newPassword')]) }}</strong>
            </div>
        @endif
        @include('admin.v2.partials.components.errors')
        <div class="block">
            <div class="tabs">
                <div class="mobile-dropdown">
                    <div class="mobile-dropdown__desc">
                        <ul class="tabs-titles">
                            <li class="active"><a href="javascript:;"
                                                  title="{{__('admin.pages.courses.preview')}}">{{__('admin.pages.courses.preview')}}</a>
                            </li>
                            <li>
{{--                                <a href="javascript:;" title="{{__('admin.pages.courses.info_about_course')}}">{{__('admin.pages.courses.info_about_course')}}</a>--}}
                                <a href="javascript:;" title="Действия">Действия</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="tabs-contents">
                    <div class="active">
                        <iframe id="page" src="/{{$lang}}/admin/moderator-course-iframe-{{ $item->id }}" frameborder="0" width="100%" height="600px"></iframe>
                    </div>
                    <div>
                        <div class="block">
                            @if($item->status == 1)

                                <form id="course_form" action="{{ route('admin.courses.accept', ['lang' => $lang, 'item' => $item->id]) }}"
                                      method="post"
                                      enctype="multipart/form-data">
                                    {{ csrf_field() }}

                                    <p><b>{{ __('admin.pages.courses.course_status_title') }}
                                            :</b> {{ __('admin.pages.courses.'.$item->status) }}</p>
                                    <p><b>Тип курса
                                            :</b> {{ $item->getTypeName() }}</p>

                                    @if (!$item->isFree())
                                        @if ($item->isQuota())
                                            <p><b>{{ __('admin.pages.courses.course_quota_title') }}
                                                    :</b> Да</p>
                                            <p><b>{{ __('admin.pages.courses.course_quota_cost') }}
                                                    :</b> {{ number_format(\App\Extensions\CalculateQuotaCost::calculate_quota_cost($item), 0, '', ' ') }} {{__('default.tenge_title')}}</p>
                                        @endif
                                        <p><b>Стоимость курса на платной основе
                                                :</b> {{ number_format($item->cost, 0, '', ' ') }} {{__('default.tenge_title')}}</p>
                                    @endif

                                    <br>
                                    <div class="buttons">
                                        <div>
                                            <button type="submit" name="action" value="activate"
                                                    class="btn btn--green">Одобрить</button>
                                        </div>
                                        @if($item->status != 2)
                                            <div>
                                                {{--<button type="submit" name="action" value="reject" class="btn btn--red btn--delete">{{ __('admin.pages.authors.reject_button') }}</button>--}}
                                                <a href="#"
                                                   title="{{ __('admin.pages.courses.reject_button_title') }}"
                                                   class="btn btn--red btn--delete"
                                                   id="rejectBtn">{{ __('admin.pages.courses.reject_button_title') }}</a>
                                            </div>
                                        @endif
                                    </div>
                                </form>
                            @elseif($item->status == 3)

                                <p><b>{{ __('admin.pages.courses.course_status_title') }}
                                        :</b> {{ __('admin.pages.courses.'. $item->status) }}</p>
                                <p><b>Тип курса
                                        :</b> {{ $item->getTypeName() }}</p>

                                @if (!$item->isFree())
                                    @if ($item->isQuota())
                                        <p><b>{{ __('admin.pages.courses.course_quota_title') }}
                                                :</b> Да</p>
                                        <p><b>{{ __('admin.pages.courses.course_quota_cost') }}
                                                :</b> {{ number_format(\App\Extensions\CalculateQuotaCost::calculate_quota_cost($item), 0, '', ' ') }} {{__('default.tenge_title')}}</p>
                                    @endif
                                    <p><b>Стоимость курса на платной основе
                                            :</b> {{ number_format($item->cost, 0, '', ' ') }} {{__('default.tenge_title')}}</p>
                                @endif

                                @if ($item->isFree() and !$item->isFreeContractCreated())
                                    <form id="course_form" action="{{ route('admin.contracts.routing.start', ['lang' => $lang, 'course_id' => $item->id, 'type' => 'agreement_free']) }}" method="post">
                                        @csrf
                                        <div class="buttons">
                                            <div>
                                                <a href="{{ route('admin.contracts.generate_preview_contract', ['lang' => $lang, 'course_id' => $item->id, 'type' => 'agreement_free']) }}" target="_blank" class="btn btn--blue">Предварительный просмотр договора (бесплатный)</a>
                                                <button type="submit" name="action" class="btn btn--green">Одобрить договор</button>
                                            </div>
                                        </div>
                                        <br>
                                    </form>
                                @else
                                    @if ($item->isFreeContractCreated())
                                        <div class="buttons">
                                            <div>
                                                <a href="{{ route('admin.contracts.view', ['lang' => $lang, 'contract_id' => $item->free_contract->id]) }}" target="_blank" class="btn btn--blue">Договор (бесплатный)</a>
                                                <button type="submit" name="action" class="btn" style="background: rgb(223, 223, 223);">Одобрить договор</button>
                                            </div>
                                        </div>
                                    @endif
                                @endif

                                @if ($item->isPaid() and !$item->isPaidContractCreated())
                                    <form id="course_form" action="{{ route('admin.contracts.routing.start', ['lang' => $lang, 'course_id' => $item->id, 'type' => 'agreement_paid']) }}" method="post">
                                        @csrf
                                        <div class="buttons">
                                            <div>
                                                <a href="{{ route('admin.contracts.generate_preview_contract', ['lang' => $lang, 'course_id' => $item->id, 'type' => 'agreement_paid']) }}" target="_blank" class="btn btn--blue">Предварительный просмотр договора (платный)</a>
                                                <button type="submit" name="action" class="btn btn--green">Одобрить договор</button>
                                            </div>
                                        </div>
                                        <br>
                                    </form>
                                @else
                                    @if ($item->isPaidContractCreated())
                                        <div class="buttons">
                                            <div>
                                                <a href="{{ route('admin.contracts.view', ['lang' => $lang, 'contract_id' => $item->paid_contract->id]) }}" target="_blank" class="btn btn--blue">Договор (платный)</a>
                                                <button type="submit" name="action" class="btn" style="background: rgb(223, 223, 223);">Одобрить договор</button>
                                            </div>
                                        </div>
                                    @endif
                                @endif

                                @if ($item->isQuota() and !$item->isQuotaContractCreated())
                                    <form id="course_form" action="{{ route('admin.contracts.routing.start', ['lang' => $lang, 'course_id' => $item->id, 'type' => 'agreement_quota']) }}" method="post">
                                        @csrf
                                        <div class="buttons">
                                            <div>
                                                <a href="{{ route('admin.contracts.generate_preview_contract', ['lang' => $lang, 'course_id' => $item->id, 'type' => 'agreement_quota']) }}" target="_blank" class="btn btn--blue">Предварительный просмотр договора (при гос.поддержке)</a>
                                                <button type="submit" name="action" class="btn btn--green">Одобрить договор</button>
                                            </div>
                                        </div>
                                        <br>
                                    </form>
                                @else
                                    @if ($item->isQuotaContractCreated())
                                        <div class="buttons">
                                            <div>
                                                <a href="{{ route('admin.contracts.view', ['lang' => $lang, 'contract_id' => $item->quota_contract->id]) }}" target="_blank" class="btn btn--blue">Договор (при гос.поддержке)</a>
                                                <button type="submit" name="action" class="btn" style="background: rgb(223, 223, 223);">Одобрить договор</button>
                                            </div>
                                        </div>
                                    @endif
                                @endif

                                @if($item->isPaid() and !$item->isQuota())
                                    <form id="course_form"
                                          action="/{{$lang}}/admin/course/quota_request/{{ $item->id }}"
                                          method="post"
                                          style="float: right"
                                          enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <div class="buttons">
                                            <div>
                                                <button type="submit" name="action" value="activate"
                                                        class="btn btn--yellow">{{ __('admin.pages.courses.quota_request_title') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                @endif

{{--                            Прошедшие проверку и отправленные на генерацию договора    --}}
                            @elseif($item->status == 5)

{{--                                {{ dd($item->isQuota()) }}--}}

                                <p><b>{{ __('admin.pages.courses.course_status_title') }}
                                        :</b> {{ __('admin.pages.courses.'. $item->status) }}</p>
                                <p><b>Тип курса
                                        :</b> {{ $item->getTypeName() }}</p>

                                @if (!$item->isFree())
                                    @if ($item->isQuota())
                                        <p><b>{{ __('admin.pages.courses.course_quota_title') }}
                                                :</b> Да</p>
                                        <p><b>{{ __('admin.pages.courses.course_quota_cost') }}
                                                :</b> {{ number_format(\App\Extensions\CalculateQuotaCost::calculate_quota_cost($item), 0, '', ' ') }} {{__('default.tenge_title')}}</p>
                                    @endif
                                    <p><b>Стоимость курса на платной основе
                                            :</b> {{ number_format($item->cost, 0, '', ' ') }} {{__('default.tenge_title')}}</p>
                                @endif

                                @if ($item->isFree())
                                    <p><b>Статус договора (бесплатный):</b> {{ !empty($item->free_contract->status) ? $item->free_contract->getStatusName() : 'Проверка договора' }}</p>
                                @endif

                                @if ($item->isPaid())
                                    <p><b>Статус договора (платный):</b> {{ !empty($item->paid_contract->status) ? $item->paid_contract->getStatusName() : 'Проверка договора' }}</p>
                                @endif

                                @if ($item->isQuota())
                                    <p><b>Статус договора (гос. поддержка):</b> {{ !empty($item->quota_contract->status) ? $item->quota_contract->getStatusName() : 'Проверка договора' }}</p>
                                @endif


                                @if ($item->isFree() and !$item->isFreeContractCreated())
                                    <form id="course_form" action="{{ route('admin.contracts.routing.start', ['lang' => $lang, 'course_id' => $item->id, 'type' => 'agreement_free']) }}" method="post">
                                        @csrf
                                        <div class="buttons">
                                            <div>
                                                <a href="{{ route('admin.contracts.generate_preview_contract', ['lang' => $lang, 'course_id' => $item->id, 'type' => 'agreement_free']) }}" target="_blank" class="btn btn--blue">Предварительный просмотр договора (бесплатный)</a>
                                                <button type="submit" name="action" class="btn btn--green">Одобрить договор</button>
                                            </div>
                                        </div>
                                        <br>
                                    </form>
                                @else
                                    @if ($item->isFreeContractCreated())
                                        <div class="buttons">
                                            <div>
                                                <a href="{{ route('admin.contracts.view', ['lang' => $lang, 'contract_id' => $item->free_contract->id]) }}" target="_blank" class="btn btn--blue">Договор (бесплатный)</a>
                                                <button type="submit" name="action" class="btn" style="background: rgb(223, 223, 223);">Одобрить договор</button>
                                            </div>
                                        </div>
                                    @endif
                                @endif

                                @if ($item->isPaid() and !$item->isPaidContractCreated())
                                    <form id="course_form" action="{{ route('admin.contracts.routing.start', ['lang' => $lang, 'course_id' => $item->id, 'type' => 'agreement_paid']) }}" method="post">
                                        @csrf
                                        <div class="buttons">
                                            <div>
                                                <a href="{{ route('admin.contracts.generate_preview_contract', ['lang' => $lang, 'course_id' => $item->id, 'type' => 'agreement_paid']) }}" target="_blank" class="btn btn--blue">Предварительный просмотр договора (платный)</a>
                                                <button type="submit" name="action" class="btn btn--green">Одобрить договор</button>
                                            </div>
                                        </div>
                                        <br>
                                    </form>
                                @else
                                    @if ($item->isPaidContractCreated())
                                        <div class="buttons">
                                            <div>
                                                <a href="{{ route('admin.contracts.view', ['lang' => $lang, 'contract_id' => $item->paid_contract->id]) }}" target="_blank" class="btn btn--blue">Договор (платный)</a>
                                                <button type="submit" name="action" class="btn" style="background: rgb(223, 223, 223);">Одобрить договор</button>
                                            </div>
                                        </div>
                                    @endif
                                @endif

                                @if ($item->isQuota() and !$item->isQuotaContractCreated())
                                    <form id="course_form" action="{{ route('admin.contracts.routing.start', ['lang' => $lang, 'course_id' => $item->id, 'type' => 'agreement_quota']) }}" method="post">
                                        @csrf
                                        <div class="buttons">
                                            <div>
                                                <a href="{{ route('admin.contracts.generate_preview_contract', ['lang' => $lang, 'course_id' => $item->id, 'type' => 'agreement_quota']) }}" target="_blank" class="btn btn--blue">Предварительный просмотр договора (при гос.поддержке)</a>
                                                <button type="submit" name="action" class="btn btn--green">Одобрить договор</button>
                                            </div>
                                        </div>
                                        <br>
                                    </form>
                                @else
                                    @if ($item->isQuotaContractCreated())
                                        <div class="buttons">
                                            <div>
                                                <a href="{{ route('admin.contracts.view', ['lang' => $lang, 'contract_id' => $item->quota_contract->id]) }}" target="_blank" class="btn btn--blue">Договор (при гос.поддержке)</a>
                                                <button type="submit" name="action" class="btn" style="background: rgb(223, 223, 223);">Одобрить договор</button>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            @endif
                        </div>
                    </div>
                    <form action="{{ route('admin.courses.reject', ['lang' => $lang, 'item' => $item->id]) }}" method="POST" id="dialog-confirm-reject" class="modal" style="display: none;">
                        @csrf
                        <h4 class="title-secondary">{{ __('admin.pages.courses.reject_title') }}</h4>
                        <hr>
                        <div class="input-group" id="rejectMessageBlock">
                            <textarea name="rejectMessage" id="rejectMessage" placeholder="Сообщение об отказе публикации" class="input-regular"></textarea>
                        </div>
                        <div class="buttons justify-end">
                            <div>
                                <button class="btn btn--red btn--delete">{{ __('admin.pages.courses.reject_button_title_1') }}</button>
                                {{--<button type="submit" name="action" value="reject" class="btn btn--red">{{ __('admin.pages.deleting.submit') }}</button>--}}
                            </div>
                            <div>
                                <button class="btn" data-fancybox-close>{{ __('admin.labels.cancel') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
{{--        </form>--}}
    </div>

@endsection

@section('scripts')
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
                    url: "/ajaxUploadImage?_token={{ csrf_token() }}",
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
    <script type="text/javascript">
        // Selecting the iframe element
        var iframe = document.getElementById("page");

        // Adjusting the iframe height onload event
        iframe.onload = function () {
            iframe.style.height = iframe.contentWindow.document.body.scrollHeight + 'px';
        }
    </script>
@endsection
