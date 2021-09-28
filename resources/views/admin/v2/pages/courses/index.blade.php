@extends('admin.v2.layout.default.template')

@section('title', $title .' | '.__('admin.site_name'))

@section('head')

@endsection

@section('content')
    <div class="container container-fluid">
        <div class="title-block">
            <div class="row row--multiline align-items-center">
                <div class="col-md-4">
                    <h1 class="title-primary" style="margin-bottom: 0">{{ $title }}</h1>
                </div>
                <div class="col-md-8 text-right-md text-right-lg">
                    <div class="flex-form">
                        <div>
                            <form action="/{{$lang}}/admin/courses/index" method="get" class="input-button">
                                <input type="text" name="term"
                                       placeholder="{{ __('admin.pages.courses.course_name') }}"
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

            @if(count($items) > 0)
                <table class="table records">
                    <colgroup>
{{--                        <col span="1" style="width: 5%;">--}}
                        <col span="1" style="width: 10%;">
                        <col span="1" style="width: 7%;">
                        <col span="1" style="width: 7%;">
                        <col span="1" style="width: 25%;">
                        <col span="1" style="width: 15%;">
                        <col span="1" style="width: 15%;">
                        <col span="1" style="width: 8%;">
                        <col span="1" style="width: 8%;">
                        <col span="1" style="width: 8%;">
                        <col span="1" style="width: 8%;">
                        <col span="1" style="width: 8%;">
                    </colgroup>
                    <thead>
                    <tr>
{{--                        <th>#</th>--}}
                        {{--                        <th>{{ __('admin.pages.user.surname').' '.__('admin.pages.user.name').' '.__('admin.pages.user.middle_name') }}</th>--}}
                        <th>{{ __('admin.pages.courses.course_name') }}</th>
                        <th>{{ __('admin.pages.courses.author_email') }}</th>
                        <th>Статус курса</th>
                        <th>Причина отклонения</th>
                        <th>Тип курса</th>
                        <th>Стоимость курса</th>
                        <th>Стоимость при гос. поддержке</th>
                        <th>Дата публикации</th>
                        <th>{{ __('admin.labels.created_at') }}</th>
                        <th>{{ __('admin.labels.updated_at') }}</th>
                        <th>{{ __('admin.labels.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        @php($creator = $item->creator)
                        @php($modifier = $item->modifier)
                        @php($remover = $item->remover)
                        @php($notification = App\Models\Notification::where('course_id', '=', $item->id)->where('name', '=', 'notifications.course_reject')->first())
                        <tr>
{{--                            <td>{{ $item->id }}</td>--}}
                            {{--                            <td>{{ $item->surname.' '.$item->name.' '.$item->middle_name }}</td>--}}
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->user()->first()->email }}</td>
                            <td>
                                @if($item->status == 1)
                                    <div class="alert alert-warning" style="margin: 0;">
                                        @elseif($item->is_activate == 2)
                                            <div class="alert alert-danger" style="margin: 0;">
                                                @elseif($item->is_activate == 3)
                                                @else
                                                    <div class="alert alert-success" style="margin: 0;">
                                                        @endif
                                                        {{ __("admin.pages.courses.$item->status") }}
                                                    </div>
                                            </div>
                                    </div>
                            </td>

                            <td>{{ isset($notification->data) ? json_decode($notification->data)[0]->course_reject_message : '' }}</td>
                            <td>{{ $item->getTypeName() }}</td>
                            <td>{{ number_format($item->cost, 0, '', ' ') }}</td>
                            <td>{{ number_format($item->calculateQuotaCost(), 0, '', ' ') }}</td>

                            <td>{{ $item->publish_at ?? '-' }}</td>
                            <td>{!! $item->created_at . ($creator ? '<br>'.($creator->surname.' '.$creator->name.' '.$creator->middle_name) : '') !!}</td>
                            <td>{!! $item->updated_at . ($modifier ? '<br>'.($modifier->surname.' '.$modifier->name.' '.$modifier->middle_name) : '') !!}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="/{{$lang}}/admin/course/{{$item->id}}" title="{{ __('admin.labels.view') }}"
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
                    </tbody>
                </table>
            @endif

            <div class="text-right">
                {{ $items->appends(['term' => $term])->links('vendor.pagination.bootstrap') }}
            </div>
        </div>
    </div>

    @include('admin.v2.partials.modals.form_delete')
@endsection

@section('scripts')

@endsection
