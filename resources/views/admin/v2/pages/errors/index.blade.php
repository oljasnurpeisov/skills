@extends('admin.v2.layout.default.template')

@section('title',__('default.pages.errors.title').' | '.__('default.site_name'))

@section('head')

@endsection

@section('content')
    <div class="container container-fluid">
        <div class="title-block">
            <div class="row row--multiline align-items-center">
                <div class="col-md-4">
                    <h1 class="title-primary" style="margin-bottom: 0">{{ __('default.pages.errors.title') }}</h1>
                </div>
                <div class="col-md-8 text-right-md text-right-lg">

                </div>
            </div>
        </div>

        @include('admin.v2.partials.components.warning')
        <div class="block">
            <h2 class="title-secondary">{{ __('default.labels.record_list') }}</h2>

            @if(count($items) > 0)
                <table class="table records">
                    <colgroup>
                        <col span="1" style="width: 3%;">
                        <col span="1" style="width: 20%;">
                        <col span="1" style="width: 12%;">
                        <col span="1" style="width: 20%;">
                        <col span="1" style="width: 15%;">
                        <col span="1" style="width: 15%;">
                        <col span="1" style="width: 15%;">
                    </colgroup>
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('default.pages.error.type') }}</th>
                        <th>{{ __('default.pages.error.target_id') }}</th>
                        <th>{{ __('default.pages.error.description') }}</th>
                        <th>{{ __('default.pages.error.status') }}</th>
                        <th>{{ __('default.labels.created_at') }}</th>
                        <th>{{ __('default.labels.updated_at') }}</th>
                        <th>{{ __('default.labels.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->types()[$item->type] }}</td>
                            <td><a href="/admin/{{ $item->type }}/{{ $item->target_id }}">{{ $item->target_id }}</a>
                            </td>
                            <td>{{ $item->description }}</td>
                            <td>
                                <div class="alert {{ ($item->processed) ? 'alert-success' : 'alert-danger' }}"
                                     style="margin: 0;">
                                    {{ __('default.pages.error.statuses.'.(int)$item->processed) }}
                                </div>
                            </td>
                            <td>{{ $item->created_at }}</td>
                            <td>{{ $item->updated_at }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="/admin/error/{{ $item->id }}"
                                       title="{{ __('default.labels.view') }}"
                                       class="icon-btn icon-btn--green icon-eye"></a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif

            <div class="text-right">
                {{ $items->appends([])->links('vendor.pagination.bootstrap') }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')

@endsection
