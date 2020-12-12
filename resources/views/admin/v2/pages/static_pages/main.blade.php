@extends('admin.v2.layout.default.template')

@section('title',__('admin.pages.static_pages.edit_pages').' | '.__('default.site_name'))

@section('content')
    <div class="container container-fluid">
        <ul class="breadcrumbs">
            <li>{{ __('admin.pages.static_pages.title') }}
            </li>
            <li class="active">{{ __('admin.pages.static_pages.main') }}</li>
        </ul>
        @include('admin.v2.partials.components.warning')
        @include('admin.v2.partials.components.errors')
        <form id="mainEdit" action="/{{$lang}}/admin/static-pages/main-update" method="post"
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
                                <h2 class="title-secondary">{{__('admin.pages.static_pages.step_by_step')}}</h2>
                                <div id="steps_{{$lang_key}}">
                                    @foreach(json_decode($item->getAttribute('data_'.$lang_key))->step_by_step as $key => $step)
                                        <div class="input-group">
                                            <label class="input-group__title">{{__('admin.pages.static_pages.step_title')}} @if($key == 0)
                                                    *@endif</label>
                                            <input type="text" name="steps_{{$lang_key}}[]" value="{{ $step->name }}"
                                                   placeholder="{{__('admin.pages.static_pages.step_title_placeholder')}}"
                                                   class="input-regular" {{$key == 0 ? 'required' : ''}}>
                                        </div>
                                        <div class="input-group">
                                            <label for="description"
                                                   class="input-group__title">{{__('admin.pages.static_pages.step_description')}} @if($key == 0)
                                                    *@endif</label>
                                            <textarea name="descriptions_{{$lang_key}}[]"
                                                      placeholder="{{__('admin.pages.static_pages.step_description_placeholder')}}"
                                                      class="input-regular" {{$key == 0 ? 'required' : ''}}>{{ $step->description }}</textarea>
                                        </div>
                                        <hr>
                                    @endforeach
                                </div>
                                <div class="buttons">
                                    <div>
                                        @if(count(json_decode($item->getAttribute('data_'.$lang_key))->step_by_step) < 6)
                                            <button type="button" id="add_step_{{$lang_key}}"
                                                    class="btn btn--blue">{{ __('admin.pages.static_pages.add_btn') }}</button>
                                        @endif
                                    </div>
                                </div>
                                <br>
                                <h2 class="title-secondary">{{__('admin.pages.static_pages.for_authors')}}</h2>
                                <div class="input-group">
                                    <label for="for_authors_description"
                                           class="input-group__title">{{__('admin.pages.static_pages.step_description')}}
                                        *</label>
                                    <textarea name="for_authors_description_{{$lang_key}}"
                                              placeholder="{{__('admin.pages.static_pages.step_description_placeholder')}}"
                                              class="input-regular"
                                              required>{{json_decode($item->getAttribute('data_'.$lang_key))->for_authors->description}}</textarea>
                                </div>
                                <div class="input-group">
                                    <label class="input-group__title">{{__('admin.pages.static_pages.btn_title')}}
                                        *</label>
                                    <input type="text" name="for_authors_btn_title_{{$lang_key}}"
                                           value="{{json_decode($item->getAttribute('data_'.$lang_key))->for_authors->btn_title}}"
                                           placeholder="{{__('admin.pages.static_pages.step_title_placeholder')}}"
                                           class="input-regular" required>
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
        const max_steps = 6

        for(language of languages){
            console.log(language);
            const step_name = '<div class="input-group">' +
                '                            <label class="input-group__title">{{__('admin.pages.static_pages.step_title')}}</label>' +
                '                            <input type="text" name="steps_'+language+'[]" value=""' +
                '                                   placeholder="{{__('admin.pages.static_pages.step_title_placeholder')}}"' +
                '                                   class="input-regular">' +
                '                        </div>'
            const step_description = '<div class="input-group">' +
                '                            <label for="description"' +
                '                                   class="input-group__title">{{__('admin.pages.static_pages.step_description')}}</label>' +
                '                            <textarea name="descriptions_'+language+'[]"' +
                '                                      placeholder="{{__('admin.pages.static_pages.step_description_placeholder')}}"' +
                '                                      class="input-regular"></textarea>' +
                '                        </div>'

            $("#add_step_"+language).click(function () {
                console.log(language)
                if ($("[name='steps[]']").length < max_steps) {
                    $("#steps_"+language).append(step_name, step_description);
                }
                if ($("[name='steps[]']").length === max_steps) {
                    $("#add_step_+language").remove();
                }
            });
        }

    </script>
@endsection
