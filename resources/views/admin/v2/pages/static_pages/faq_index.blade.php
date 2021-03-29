@extends('admin.v2.layout.default.template')

@section('title',__('admin.pages.static_pages.faq').' | '.__('default.site_name'))

@section('head')

@endsection

@section('content')
    <div class="container container-fluid">
{{--        <div class="title-block">--}}
{{--            <div class="row row--multiline align-items-center">--}}
{{--                <div class="col-md-4">--}}
{{--                    <h1 class="title-primary" style="margin-bottom: 0">{{ __('admin.pages.roles.title')  }}</h1>--}}
{{--                </div>--}}
{{--                <div class="col-md-8 text-right-md text-right-lg">--}}
{{--                    <div class="flex-form">--}}
{{--                        <div>--}}
{{--                            <form action="/{{$lang}}/admin/role/index" method="get" class="input-button">--}}
{{--                                <input type="text" name="term"--}}
{{--                                       placeholder="{{ __('admin.pages.role.name') }}"--}}
{{--                                       class="input-regular input-regular--solid" style="width: 282px;"--}}
{{--                                       value="{{ $term }}">--}}
{{--                                <button class="btn btn--green">{{ __('admin.labels.search') }}</button>--}}
{{--                            </form>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

        @include('admin.v2.partials.components.warning')
        <div class="block">
            <h2 class="title-secondary">FAQ</h2>

{{--            @if(count(json_decode($items)) > 0)--}}
                <table class="table records">
                    <colgroup>
                        <col span="1" style="width: 20%;">
                        <col span="1" style="width: 20%;">
                        <col span="1" style="width: 20%;">
                        <col span="1" style="width: 7%;">
                    </colgroup>
                    <thead>
                    <tr>
{{--                        <th>#</th>--}}
                        <th>{{__('admin.pages.static_pages.faq_theme_title')}} (Русский)</th>
                        <th>{{__('admin.pages.static_pages.faq_theme_title')}} (Қазақша)</th>
                        <th>{{__('admin.pages.static_pages.faq_theme_title')}} (English)</th>
                        <th>{{ __('admin.labels.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $key => $item)
                        <tr>
{{--                            <td>{{ $key }}</td>--}}
                            <td>{{ $item[0] }}</td>
                            <td>{{ $item[1] ?? '' }}</td>
                            <td>{{ $item[2] ?? ''}}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="/{{$lang}}/admin/static-pages/faq-view/{{$key}}" title="{{ __('admin.labels.view') }}"
                                       class="icon-btn icon-btn--yellow icon-eye"></a>
                                    <a href="/{{$lang}}/admin/static-pages/delete-faq-theme/{{$key}}" class="icon-btn icon-btn--pink icon-delete">
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            <a href="/{{$lang}}/admin/static-pages/faq-create" class="btn btn--blue title-secondary">{{__('admin.pages.static_pages.add_theme_btn')}}
            </a>
{{--            @endif--}}

            <div class="text-right">
{{--                {{ $items->links('vendor.pagination.bootstrap') }}--}}
            </div>
        </div>
    </div>

    @include('admin.v2.partials.modals.form_delete')
@endsection

@section('scripts')

@endsection
