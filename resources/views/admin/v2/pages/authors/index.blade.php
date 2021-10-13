@extends('admin.v2.layout.default.template')

@section('title',__('admin.pages.authors.title').' | '.__('admin.site_name'))

@section('head')

@endsection

@section('content')
    <div class="container container-fluid">
        <div class="title-block">
            <div class="row row--multiline align-items-center">
                <div class="col-md-4">
                    <h1 class="title-primary" style="margin-bottom: 0">{{ __('admin.pages.authors.title')  }}</h1>
                </div>
            </div>
        </div>

        @include('admin.v2.partials.components.warning')
        <div class="block">
            <h2 class="title-secondary">{{ $term ? __('admin.labels.search_result') : __('admin.labels.record_list') }}</h2>


            <table class="table records">
                <colgroup>
                    <col span="1" style="width: 5%;">
                    <col span="1" style="width: 20%;">
                    <col span="1" style="width: 20%;">
                    <col span="1" style="width: 7%;">
                    <col span="1" style="width: 7%;">
                    <col span="1" style="width: 7%;">
                    <col span="1" style="width: 15%;">
                    <col span="1" style="width: 15%;">
                    <col span="1" style="width: 8%;">
                    <col span="1" style="width: 8%;">
                    <col span="1" style="width: 8%;">
                </colgroup>
                <thead>
                <tr>
                    <th>#</th>
                    {{--                        <th>{{ __('admin.pages.user.surname').' '.__('admin.pages.user.name').' '.__('admin.pages.user.middle_name') }}</th>--}}
                    <th>ФИО</th>
                    <th>{{ __('admin.pages.user.iin_bin') }}</th>
                    <th>{{ __('admin.pages.user.email') }}</th>
                    <th>{{ __('admin.pages.user.role') }}</th>
                    <th>Организация</th>
                    <th>{{ __('admin.pages.user.status') }}</th>
                    <th>{{ __('admin.labels.created_at') }}</th>
                    <th>{{ __('admin.labels.updated_at') }}</th>
                    <th>{{ __('admin.labels.actions') }}</th>
                </tr>
                </thead>
                <tbody>
                <form name="search">
                    <input type="hidden" name="type" value="authors">
                    <tr>
                        <th></th>
                        <th><input name="fio" type="text" class="input-regular" value="{{ $request['fio'] ?? '' }}">
                        </th>
                        <th><input name="iin_bin" onfocus="$(this).inputmask('999999999999')" type="text" class="input-regular" value="{{ $request['iin_bin'] ?? '' }}">
                        <th><input name="email" type="text" class="input-regular" value="{{ $request['email'] ?? '' }}">
                        </th>
                        <th></th>
                        <th><input name="company_name" type="text" class="input-regular"
                                   value="{{ $request['company_name'] ?? '' }}"></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>
                            <div class="buttons btn-group-sm">
                                <a href="{{ route(Route::currentRouteName(), ['lang' => $lang]) }}" class="btn"
                                   style="color: #fff; background: #e2e2e2; text-decoration:none; height: 30px; margin-top: 2px; margin-right: 5px">Сбросить</a>
                                <button class="btn">Поиск</button>
                            </div>
                        </th>
                    </tr>
                </form>
                @if(count($items) > 0)
                    @foreach($items as $item)
                        @php($creator = $item->creator)
                        @php($modifier = $item->modifier)
                        @php($remover = $item->remover)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->user_information->name ?? '' }} {{ $item->user_information->surname ?? '' }} {{ $item->user_information->patronymic ?? '' }}</td>
                            <td>{{ $item->iin }}</td>
                            <td><a href="mailto:{{ $item->email }}" target="_blank">{{ $item->email }}</a></td>
                            <td>{{ ($item->roles()->first()) ? $item->roles()->first()->name : '-' }}</td>
                            <td>{{ $item->company_name }}</td>
                            {{--@if($item->is_activate == 0)--}}
                            {{--<td>Ожидает подтверждения</td>--}}
                            {{--@elseif($item->is_activate == 1)--}}
                            {{--<td>Активирован</td>--}}
                            {{--@elseif($item->is_activate == 2)--}}
                            {{--<td>Отклонен</td>--}}
                            {{--@else--}}
                            {{--<td></td>--}}
                            {{--@endif--}}
                            <td>
                                @if($item->email_verified_at == null)
                                    <div class="alert alert-warning" style="margin: 0;">
                                        {{ __('admin.pages.user.statuses.0') }}
                                    </div>
                                @else
                                    <div class="alert alert-success" style="margin: 0;">
                                        {{ __('admin.pages.user.statuses.1') }}
                                    </div
                                @endif
                            </td>
                            <td>{!! $item->created_at . ($creator ? '<br>'.($creator->surname.' '.$creator->name.' '.$creator->middle_name) : '') !!}</td>
                            <td>{!! $item->updated_at . ($modifier ? '<br>'.($modifier->surname.' '.$modifier->name.' '.$modifier->middle_name) : '') !!}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="/{{$lang}}/admin/author/{{$item->id}}"
                                       title="{{ __('admin.labels.view') }}"
                                       class="icon-btn icon-btn--yellow icon-eye"></a>
                                    {{--<a href="/{{$lang}}/admin/author/{{$item->id}}" title="{{ __('admin.labels.edit') }}"--}}
                                    {{--class="icon-btn icon-btn--green icon-check"></a>--}}
                                    {{--<a href="/{{$lang}}/admin/author/{{ $item->id }}"--}}
                                    {{--title="{{ __('admin.pages.deleting.submit') }}"--}}
                                    {{--class="icon-btn icon-btn--pink icon-delete"></a>--}}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>

            <div class="text-right">
                {{ $items->appends(['term' => $term])->links('vendor.pagination.bootstrap') }}
            </div>
        </div>
    </div>

    @include('admin.v2.partials.modals.form_delete')
@endsection

@section('scripts')

@endsection
