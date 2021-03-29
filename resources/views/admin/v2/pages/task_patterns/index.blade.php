@extends('admin.v2.layout.default.template')

@section('title',__('default.pages.task_patterns.title').' | '.__('default.site_name'))

@section('head')

@endsection

@section('content')
    <div class="container container-fluid">
        <div class="title-block">
            <div class="row row--multiline align-items-center">
                <div class="col-md-4">
                    <h1 class="title-primary"
                        style="margin-bottom: 0">{{ __('default.pages.task_patterns.title')  }}</h1>
                </div>
                <div class="col-md-8 text-right-md text-right-lg">
                    <div class="flex-form">
                        <div>
                            <form action="/admin/task/pattern/index" method="get" class="input-button">
                                <input type="text" name="term"
                                       placeholder="{{ __('default.pages.task_pattern.name') }}"
                                       class="input-regular input-regular--solid" style="width: 282px;"
                                       value="{{ $term }}">
                                <button class="btn btn--green">{{ __('default.labels.search') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('admin.v2.partials.components.warning')
        <div class="block">
            <h2 class="title-secondary">{{ $term ? __('default.labels.search_result') : __('default.labels.record_list') }}</h2>

            @if(count($items) > 0)
                <table class="table records">
                    <colgroup>
                        <col span="1" style="width: 5%;">
                        <col span="1" style="width: 15%;">
                        <col span="1" style="width: 25%;">
                        <col span="1" style="width: 25%;">
                        <col span="1" style="width: 10%;">
                        <col span="1" style="width: 10%;">
                        <col span="1" style="width: 10%;">
                    </colgroup>
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('default.pages.task_pattern.name') }}</th>
                        <th>{{ __('default.pages.task_pattern.out') }}</th>
                        <th>{{ __('default.pages.task_pattern.in') }}</th>
                        <th>{{ __('default.labels.created_at') }}</th>
                        <th>{{ __('default.labels.updated_at') }}</th>
                        <th>{{ __('default.labels.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td>
                                @foreach($item->dishes as $dish)
                                    @if($dish->pivot->type === 'unload')
                                        {{ $dish->name }}<br>
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach($item->dishes as $dish)
                                    @if($dish->pivot->type === 'load')
                                        {{ $dish->name }} x {{ $dish->pivot->count }} шт<br>
                                    @endif
                                @endforeach
                            </td>
                            <td>{!! $item->created_at !!}</td>
                            <td>{!! $item->updated_at !!}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="/admin/task/pattern/{{$item->id}}" title="{{ __('default.labels.edit') }}"
                                       class="icon-btn icon-btn--yellow icon-edit"></a>
                                    <a href="/admin/task/pattern/{{ $item->id }}"
                                       title="{{ __('default.pages.deleting.submit') }}"
                                       class="icon-btn icon-btn--pink icon-delete"></a>
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
