@extends('admin.v2.layout.default.template')

@section('title',__('admin.pages.static_pages.edit_pages').' | '.__('default.site_name'))

@section('content')
    <div class="container container-fluid">
        <ul class="breadcrumbs">
            <li><a href="/{{$lang}}/admin/static-pages/help-index">{{ __('admin.pages.static_pages.help') }}</a>
            </li>
            <li class="active">{{ __('admin.pages.static_pages.edit_theme_title') }}</li>
        </ul>
        @include('admin.v2.partials.components.warning')
        @include('admin.v2.partials.components.errors')
        <form id="mainEdit" action="/{{$lang}}/admin/static-pages/update-help-view/{{$item->id}}/{{$theme_key}}"
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
                                <h2 class="title-secondary">{{__('admin.pages.static_pages.help')}}</h2>
                                <div id="help_{{$lang_key}}">
                                    <div class="input-group">
                                        <label class="input-group__title">{{__('admin.pages.static_pages.faq_theme_title')}} @if($lang_key == 'ru')*@endif</label>
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
                                                    <label class="input-group__title">{{__('admin.pages.static_pages.tab_title')}} @if($lang_key == 'ru')
                                                            *@endif</label>
                                                    <input type="text" name="tab_name_{{$lang_key}}[]"
                                                           value="{{$tab['name']}}"
                                                           placeholder="{{__('admin.pages.static_pages.tab_title')}}"
                                                           class="input-regular" {{$lang_key == 'ru' ? 'required' : ''}}>
                                                </div>
                                                <div class="input-group">
                                                    <label for="description"
                                                           class="input-group__title">{{__('admin.pages.static_pages.tab_description')}} @if($lang_key == 'ru')
                                                            *@endif</label>
                                                    <textarea name="tab_description_{{$lang_key}}[]"
                                                              placeholder="{{__('admin.pages.static_pages.tab_description')}}"
                                                              class="input-regular" {{$lang_key == 'ru' ? 'required' : ''}}>{{ $tab['description'] }}</textarea>
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
                '                                                      class="input-regular"></textarea>' +
                '                                        </div>'
        }

        function tinymce_init() {
            tinymce.init({
                mode: "textareas",
                menubar: false,
                plugins: 'image code link',
                toolbar: 'undo redo | formatselect | bold italic link image | alignleft aligncenter alignright alignjustify | bullist numlist | removeformat | help',
                image_title: true,
                automatic_uploads: true,
                file_picker_types: 'file image media',
                file_picker_callback: function (cb, value, meta) {
                    var input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');

                    input.onchange = function () {
                        var file = this.files[0];

                        var reader = new FileReader();
                        reader.onload = function () {
                            var id = 'blobid' + (new Date()).getTime();
                            var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                            var base64 = reader.result.split(',')[1];
                            var blobInfo = blobCache.create(id, file, base64);
                            blobCache.add(blobInfo);
                            cb(blobInfo.blobUri(), {title: file.name});
                        };
                        reader.readAsDataURL(file);
                    };
                    input.click();
                },
            });
        }

        $("#add_tab_ru").click(function () {
            $("#tabs_ru").append(tab_names['ru'], tab_descriptions['ru']);
            tinymce.remove();
            tinymce_init()
        });
        $("#add_tab_kk").click(function () {
            $("#tabs_kk").append(tab_names['kk'], tab_descriptions['kk']);
            tinymce.remove();
            tinymce_init()
        });
        $("#add_tab_en").click(function () {
            $("#tabs_en").append(tab_names['en'], tab_descriptions['en']);
            tinymce.remove();
            tinymce_init()
        });
    </script>
@endsection
