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
                            <li><a href="javascript:;"
                                   title="{{__('admin.pages.courses.info_about_course')}}">{{__('admin.pages.courses.info_about_course')}}</a>
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
                                <form id="course_form" action="/{{$lang}}/admin/course/publish/{{ $item->id }}"
                                      method="post"
                                      enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <p><b>Статус курса:</b> {{ __('admin.pages.courses.'.$item->status) }}</p>
                                    <br>
                                    <div class="buttons">
                                        <div>
                                            <button type="submit" name="action" value="activate"
                                                    class="btn btn--green">{{ __('admin.pages.courses.publish_button_title') }}</button>
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
                                        :</b> {{ __('admin.pages.courses.'.$item->status) }}</p>
                                <p><b>{{ __('admin.pages.courses.course_quota_title') }}
                                        :</b> {{ __('admin.pages.courses.quota_status_'.$item->quota_status) }}</p>
                                <p><b>{{ __('admin.pages.courses.course_quota_cost') }}
                                        :</b> {{$item->quota_cost}} {{__('default.tenge_title')}}</p>

                                @switch($item->quota_status)
                                    @case(0)
                                    @case(3)
                                    @if(($item->cost > 0) and ($item->is_paid == true))
                                        <form id="course_form"
                                              action="/{{$lang}}/admin/course/quota_request/{{ $item->id }}"
                                              method="post"
                                              enctype="multipart/form-data">
                                            {{ csrf_field() }}
                                            <br>
                                            <div class="buttons">
                                                <div>
                                                    <button type="submit" name="action" value="activate"
                                                            class="btn btn--yellow">{{ __('admin.pages.courses.quota_request_title') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                    @endif
                                    @break
                                    @case(2)
                                    @case(4)
                                    <form id="quota_number_form" method="POST"
                                          action="/{{$lang}}/admin/course/quota_contract/{{ $item->id }}">
                                        {{ csrf_field() }}
                                        <div class="input-group {{ $errors->has('quota_contract_number') ? ' has-error' : '' }}">
                                            <label class="input-group__title">Номер договора*</label>
                                            <input type="text" name="quota_contract_number"
                                                   value="{{ $item->quota_contract_number ?? '' }}"
                                                   placeholder=""
                                                   class="input-regular" required>
                                            @if ($errors->has('quota_contract_number'))
                                                <span class="help-block"><strong>{{ $errors->first('quota_contract_number') }}</strong></span>
                                            @endif
                                        </div>
                                        <div>
                                            <button type="submit"
                                                    class="btn btn--green">{{ __('admin.labels.save') }}</button>
                                        </div>
                                    </form>
                                    @break
                                    @default

                                @endswitch


                                <hr>
                                <form id="course_form" action="/{{$lang}}/admin/course/unpublish/{{ $item->id }}"
                                      method="post"
                                      enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <div class="buttons" style="float: right;">
                                        <div>
                                            <button type="submit" name="action"
                                                    class="btn btn--red">{{ __('admin.pages.courses.unpublish_title') }}</button>
                                        </div>
                                    </div>
                                    <br>
                                </form>
                            @endif
                        </div>
                    </div>
                    <div id="dialog-confirm-reject" class="modal" style="display: none;">
                        <h4 class="title-secondary">{{ __('admin.pages.courses.reject_title') }}</h4>
                        <hr>
                        <div class="input-group" id="rejectMessageBlock">
                                <textarea form="course_form" name="rejectMessage" id="rejectMessage"
                                          placeholder="Сообщение об отказе публикации"
                                          class="input-regular"></textarea>
                        </div>
                        <div class="buttons justify-end">
                            <div>
                                <button type="submit" form="course_form" id="send_reject" name="action" value="reject"
                                        class="btn btn--red btn--delete">{{ __('admin.pages.courses.reject_button_title_1') }}</button>
                                {{--<button type="submit" name="action" value="reject" class="btn btn--red">{{ __('admin.pages.deleting.submit') }}</button>--}}
                            </div>
                            <div>
                                <button class="btn" data-fancybox-close>{{ __('admin.labels.cancel') }}</button>
                            </div>
                        </div>
                    </div>
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
