@extends('admin.v2.layout.default.template')

@section('title',__('admin.pages.students.title').' | '.__('admin.site_name'))

@section('head')

@endsection

@section('content')
    <div class="container container-fluid">
        <div class="title-block">
            <div class="row row--multiline align-items-center">
                <div class="col-md-4">
                    <h1 class="title-primary" style="margin-bottom: 0">{{ __('admin.pages.students.title')  }}</h1>
                </div>
                <div class="col-md-8 text-right-md text-right-lg">
                    <div class="flex-form">
                        <div>
                            <form action="/{{$lang}}/admin/student/index" method="get" class="input-button">
                                <input type="text" name="term"
                                       placeholder="{{ __('admin.pages.user.email') }}"
                                       class="input-regular input-regular--solid" style="width: 282px;"
                                       value="{{ $term }}">
                                <button class="btn btn--green">{{ __('admin.labels.search') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('admin.v2.partials.components.warning')
        <div class="block">
            <h2 class="title-secondary">{{ $term ? __('admin.labels.search_result') : __('admin.labels.record_list') }}</h2>


                <table class="table records">
                    <colgroup>
                        <col span="1" style="width: 5%;">
                        <col span="1" style="width: 15%;">
                        <col span="1" style="width: 12%;">
                        <col span="1" style="width: 15%;">
                        <col span="1" style="width: 10%;">
                        <col span="1" style="width: 8%;">
                        <col span="1" style="width: 7%;">
                        <col span="1" style="width: 10%;">
                        <col span="1" style="width: 10%;">
                        <col span="1" style="width: 8%;">
                    </colgroup>
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>ФИО</th>
                        <th>{{ __('admin.pages.user.iin') }}</th>
                        {{--                        <th>{{ __('admin.pages.user.surname').' '.__('admin.pages.user.name').' '.__('admin.pages.user.middle_name') }}</th>--}}
                        <th>{{ __('admin.pages.user.email') }}</th>
                        <th>{{ __('admin.pages.user.area_title') }}</th>
                        <th>{{ __('admin.pages.user.region_title') }}</th>
                        <th>{{ __('admin.pages.user.role') }}</th>
                        <th>{{ __('admin.labels.created_at') }}</th>
                        <th>{{ __('admin.labels.updated_at') }}</th>
                        <th>{{ __('admin.labels.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <form name="search">
                        <input type="hidden" name="type" value="students">
                        <tr>
                            <th></th>
                            <th><input name="fio" type="text" class="input-regular" value="{{ $request['fio'] ?? '' }}"></th>
                            <th><input name="iin" onfocus="$(this).inputmask('999999999999')" type="text" class="input-regular" value="{{ $request['iin'] ?? '' }}"></th>
                            <th><input name="email" type="text" class="input-regular" value="{{ $request['email'] ?? '' }}"></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>
                                <div class="buttons btn-group-sm">
                                    <a href="{{ route(Route::currentRouteName(), ['lang' => $lang]) }}" class="btn" style="color: #fff; background: #e2e2e2; text-decoration:none; height: 30px; margin-top: 2px; margin-right: 5px">Сбросить</a>
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
                                <th>{{ $item->student_information->name ?? '' }} {{ $item->student_information->surname ?? '' }} {{ $item->student_information->patronymic ?? '' }}</th>
                                <th>{{ $item->student_information->iin ?? '' }}</th>
                                {{--                            <td>{{ $item->surname.' '.$item->name.' '.$item->middle_name }}</td>--}}
                                <td><a href="mailto:{{ $item->email }}" target="_blank">{{ $item->email }}</a></td>
                                <td>{{ $item->student_information->coduoz ? App\Models\RegionTree::getRegionCaption($lang, $item->student_information->coduoz) : '' }}</td>
                                <td>{{ $item->student_information->region_id ? App\Models\Kato::where('te',  $item->student_information->region_id)->first()->rus_name ?? '' : '' }}</td>
                                <td>{{ ($item->roles()->first()) ? $item->roles()->first()->name : '-' }}</td>
                                <td>{!! $item->created_at . ($creator ? '<br>'.($creator->surname.' '.$creator->name.' '.$creator->middle_name) : '') !!}</td>
                                <td>{!! $item->updated_at . ($modifier ? '<br>'.($modifier->surname.' '.$modifier->name.' '.$modifier->middle_name) : '') !!}</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="/{{$lang}}/admin/student/{{$item->id}}" title="{{ __('admin.labels.view') }}"
                                           class="icon-btn icon-btn--yellow icon-eye"></a>
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
