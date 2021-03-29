@extends('admin.v2.layout.default.template')

@section('title',__('admin.pages.static_pages.help').' | '.__('default.site_name'))

@section('head')

@endsection

@section('content')
    <div class="container container-fluid">

        @include('admin.v2.partials.components.warning')
        <div class="block">
            <h2 class="title-secondary">{{__('admin.pages.static_pages.help')}}</h2>

            <table class="table records">
                <colgroup>
                    <col span="1" style="width: 20%;">
                    <col span="1" style="width: 20%;">
                    <col span="1" style="width: 20%;">
                    <col span="1" style="width: 7%;">
                </colgroup>
                <thead>
                <tr>
                    <th>{{__('admin.pages.static_pages.faq_theme_title')}} (Русский)</th>
                    <th>{{__('admin.pages.static_pages.faq_theme_title')}} (Қазақша)</th>
                    <th>{{__('admin.pages.static_pages.faq_theme_title')}} (English)</th>
                    <th>{{ __('admin.labels.actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $key => $item)
                    <tr>
                        <td>{{ $item[0] }}</td>
                        <td>{{ $item[1] ?? '' }}</td>
                        <td>{{ $item[2] ?? ''}}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="/{{$lang}}/admin/static-pages/help-view/{{$key}}" title="{{ __('admin.labels.view') }}"
                                   class="icon-btn icon-btn--yellow icon-eye"></a>
                                <a href="/{{$lang}}/admin/static-pages/delete-help-theme/{{$key}}" class="icon-btn icon-btn--pink icon-delete">
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <a href="/{{$lang}}/admin/static-pages/help-create" class="btn btn--blue title-secondary">{{__('admin.pages.static_pages.add_theme_btn')}}
            </a>
            <div class="text-right">
            </div>
        </div>
    </div>

    @include('admin.v2.partials.modals.form_delete')
@endsection

@section('scripts')

@endsection
