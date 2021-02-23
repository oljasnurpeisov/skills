@extends('admin.v2.layout.default.template')

@section('title',__('admin.pages.static_pages.edit_pages').' | '.__('default.site_name'))

@section('content')
    <div class="container container-fluid">
        <ul class="breadcrumbs">
            <li><a href="/{{$lang}}/admin/static-pages/faq-index">{{ __('admin.pages.static_pages.faq') }}</a>
            </li>
            <li class="active">{{ __('admin.pages.static_pages.edit_theme_title') }}</li>
        </ul>
        @include('admin.v2.partials.components.warning')
        @include('admin.v2.partials.components.errors')
        <form id="mainEdit" action="/{{$lang}}/admin/static-pages/update-faq-view/{{$item->id}}/{{$theme_key}}"
              method="post"
              enctype="multipart/form-data">
            @csrf
            @php($languages = ['ru' => 'Русский язык', 'kk' => 'Казахский язык', 'en' => 'Английский язык'])
            <div class="block">
                <div class="tabs">
                    <div class="mobile-dropdown">
                        <div class="mobile-dropdown__desc">
                            <ul class="tabs-titles">
                                @foreach($languages as $key => $language)
                                    <li class="{{$key == 'ru' ? 'active' : ''}}"><a href="javascript:;"
                                                                                    title="{{$language}}">{{$language}}</a>
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                    </div>
                    <div class="tabs-contents">
                        @foreach($languages as $lang_key => $language)
                            <div class="{{$lang_key == 'ru' ? 'active' : ''}}">
                                <h2 class="title-secondary">{{__('admin.pages.static_pages.faq')}}</h2>
                                <div id="faq_{{$lang_key}}">
                                    <div class="input-group">
                                        <label class="input-group__title">{{__('admin.pages.static_pages.faq_theme_title')}} @if($lang_key == 'ru')
                                                *@endif</label>
                                        <input type="text" name="theme_name_{{$lang_key}}"
                                               value="{{json_decode($item->getAttribute('data_'.$lang_key), true)[$theme_key]['name'] ?? ''}}"
                                               placeholder="{{__('admin.pages.static_pages.faq_theme_title')}}"
                                               class="input-regular" {{$lang_key == 'ru' ? 'required' : ''}}>
                                    </div>
                                    <hr>
                                    <div id="tabs_{{$lang_key}}">
                                        @if(!empty(json_decode($item->getAttribute('data_'.$lang_key), true)[$theme_key]['tabs']))
                                            @foreach(json_decode($item->getAttribute('data_'.$lang_key), true)[$theme_key]['tabs'] as $key => $tab)
                                                <div class="input-group">
                                                    <label class="input-group__title">{{__('admin.pages.static_pages.tab_title')}}</label>
                                                    <input type="text" name="tab_name_{{$lang_key}}[]"
                                                           value="{{$tab['name']}}"
                                                           placeholder="{{__('admin.pages.static_pages.tab_title')}}"
                                                           class="input-regular">
                                                </div>
                                                <div class="input-group">
                                                    <label for="description"
                                                           class="input-group__title">{{__('admin.pages.static_pages.tab_description')}}</label>
                                                    <textarea name="tab_description_{{$lang_key}}[]"
                                                              placeholder="{{__('admin.pages.static_pages.tab_description')}}"
                                                              class="input-regular tinymce-text-here">{{ $tab['description'] }}</textarea>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                                <div class="buttons">
                                    <div>
                                        <button type="button" id="add_tab_{{$lang_key}}"
                                                class="btn btn--blue">{{__('admin.pages.static_pages.add_tab_btn')}}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="block">
                <div class="buttons">
                    <div>
                        <button type="submit" class="btn btn--green">{{ __('admin.labels.save') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        function TinyMceInit(selector, textOnly = false) {
            let lang = window.Laravel.lang,
                baseUrl = '{{env('APP_URL')}}',
                // baseUrl = '',
                method = "/ajaxUploadImageTest",
                // method = "/ajax_upload_lesson_another_file?_token=" + window.Laravel.csrfToken,
                additionalTools = '', input, progressModal, progressBar, cancelUploadBtn, progressMsgEl,

                progressModalContent = `<div class="text-center">
                                    <h4 class="title-primary">Загрузка файла</h4>
                                    <div class="progress-bar"><span></span></div>
                                    <div class="plain-text gray"></div>
                                    <a href="javascript:;" title="Отмена" class="btn">Отмена</a>
                                </div>`;

            additionalTools = 'image media';

            if (!document.querySelector('#filePicker')) {
                input = document.createElement('input');
                input.type = 'file';
                input.id = 'filePicker';
                input.style.cssText = 'position: fixed; top: -9999px; left: -9999px; z-index: -1';
                document.querySelector('body').append(input);
            } else {
                input = document.querySelector('#filePicker');
            }

            if (!document.querySelector('#progressModal')) {
                progressModal = document.createElement('div');
                progressModal.id = 'progressModal';
                progressModal.innerHTML = progressModalContent;
                progressModal.style.display = 'none';
                document.querySelector('body').append(progressModal);
            } else {
                progressModal = document.querySelector('#progressModal');
            }

            progressBar = progressModal.querySelector('.progress-bar span');
            cancelUploadBtn = progressModal.querySelector('.btn');
            progressMsgEl = progressModal.querySelector('.plain-text');
            progressMsgEl.style.display = 'none';

            tinymce.init({
                selector: selector,
                menubar: false,
                plugins: [
                    'lists link ' + additionalTools + ' table paste code wordcount'
                ],
                toolbar: 'undo redo | formatselect | ' +
                    'bold italic link ' + additionalTools + ' | alignleft aligncenter ' +
                    'alignright alignjustify | bullist numlist | ' +
                    'removeformat | help',
                images_upload_url: method,
                files_upload_url: method,
                file_picker_types: 'file image media',
                relative_urls: false,
                language: 'ru',
                file_picker_callback: function (callback, value, meta) {
                    if (meta.filetype === 'file') {
                        input.accept = '.pdf, .doc, .xls, .ppt, .docx, .xlsx, .pptx, .png, .jpg, .rar, .zip, .7z, .mp3, .mp4, .avi, .mov';
                        pickerCallback(callback);
                    }

                    // Provide image and alt text for the image dialog
                    if (meta.filetype === 'image') {
                        input.accept = '.png, .jpg, .jpeg, .gif';
                        pickerCallback(callback, 'image');
                    }
                    // Provide alternative source and posted for the media dialog
                    if (meta.filetype === 'media') {
                        input.accept = '.mp4, .avi, .mov';
                        pickerCallback(callback, 'video');
                    }
                },
                setup: function (editor) {
                    editor.on('change', function () {
                        editor.save();
                    });
                    editor.on('Undo', function () {
                        editor.save();
                    });
                    editor.on('Redo', function () {
                        editor.save();
                    });
                }
            });

            function pickerCallback(callback, fileType = null) {
                input.click();
                input.onchange = function () {
                    let fd = new FormData();
                    let file = input.files[0];
                    fd.append('file', file);
                    let ajaxUpload = $.ajax({
                        xhr: function () {
                            let xhr = new window.XMLHttpRequest();
                            xhr.upload.addEventListener("progress", function (evt) {
                                if (evt.lengthComputable) {
                                    let percentComplete = ((evt.loaded / evt.total) * 100);
                                    progressBar.style.width = percentComplete + '%';
                                }
                            }, false);
                            return xhr;
                        },
                        url: baseUrl + method,
                        type: 'POST',
                        processData: false,
                        contentType: false,
                        data: fd,
                        beforeSend: function () {
                            progressMsgEl.style.display = 'none';
                            progressBar.style.width = 0;
                            cancelUploadBtn.addEventListener('click', abortUpload);
                            $.fancybox.open({
                                src: '#' + progressModal.id,
                                touch: false,
                                smallBtn: false,
                                buttons: [],
                                clickSlide: false,
                                clickOutside: false
                            });
                        },
                        error: function (response) {
                            console.log(response);
                            input.value = '';
                            progressBar.style.width = 0;
                            progressMsgEl.style.display = 'block';
                            progressMsgEl.innerHTML = vocabulary[lang].fail;
                        },
                        success: function (response) {
                            switch (fileType) {
                                case 'video':
                                    callback(baseUrl + response.location, {width: '100%', height: 'auto'});
                                    break;
                                case 'image':
                                    callback(baseUrl + response.location, {});
                                    break;
                                default:
                                    callback(baseUrl + response.location, {});
                                    break;
                            }
                            input.value = '';
                            parent.jQuery.fancybox.getInstance().close();
                            cancelUploadBtn.removeEventListener('click', abortUpload);
                        }
                    });

                    function abortUpload() {
                        ajaxUpload.abort();
                        input.value = '';
                        $.fancybox.close();
                        progressBar.style.width = 0;
                        cancelUploadBtn.removeEventListener('click', abortUpload);
                    }
                };
            }
        }

        if (document.querySelector('.tinymce-here')) {
            TinyMceInit('.tinymce-here');
        }
        if (document.querySelector('.tinymce-text-here')) {
            TinyMceInit('.tinymce-text-here', true);
        }
    </script>
    <script>
        languages = ['ru', 'kk', 'en']

        tab_names = [];
        tab_descriptions = [];
        for (language of languages) {
            tab_names[language] = '<div class="input-group">' +
                '                                            <label class="input-group__title">{{__('admin.pages.static_pages.tab_title')}}' +
                '                                                    </label>' +
                '                                            <input type="text" name="tab_name_' + language + '[]" value=""' +
                '                                                   placeholder="{{__('admin.pages.static_pages.tab_title')}}"' +
                '                                                   class="input-regular"' +
                '                                        </div>';

            tab_descriptions[language] = '<div class="input-group">' +
                '                                            <label for="description"' +
                '                                                   class="input-group__title">{{__('admin.pages.static_pages.tab_description')}}' +
                '                                                    </label>' +
                '                                            <textarea name="tab_description_' + language + '[]"' +
                '                                                      placeholder="{{__('admin.pages.static_pages.tab_description')}}"' +
                '                                                      class="input-regular tinymce-here"></textarea>' +
                '                                        </div>'
        }

        $("#add_tab_ru").click(function () {
            $("#tabs_ru").append(tab_names['ru'], tab_descriptions['ru']);
            TinyMceInit('.tinymce-here');
        });
        $("#add_tab_kk").click(function () {
            $("#tabs_kk").append(tab_names['kk'], tab_descriptions['kk']);
            TinyMceInit('.tinymce-here');
        });
        $("#add_tab_en").click(function () {
            $("#tabs_en").append(tab_names['en'], tab_descriptions['en']);
            TinyMceInit('.tinymce-here');
        });
    </script>
@endsection
