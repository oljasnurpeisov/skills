@extends('admin.v2.layout.default.template')

@section('title',__('admin.pages.authors.title').' | '.__('admin.site_name'))

@section('head')

@endsection

@section('content')
    <div class="container container-fluid">
        <div class="title-block">
            <div class="row row--multiline align-items-center">
                <div class="col-md-4">
                    <h1 class="title-primary" style="margin-bottom: 0">{{ __('admin.pages.dialogs.title')  }}</h1>
                </div>

            </div>
        </div>

        @include('admin.v2.partials.components.warning')
        <div class="block">
            <h2 class="title-secondary">{{ __('admin.labels.record_list') }}</h2>

            @if(count($items) > 0)
                <table class="table records">
                    <colgroup>
                        <col span="1" style="width: 5%;">
                        <col span="1" style="width: 20%;">
                        <col span="1" style="width: 20%;">
                        <col span="1" style="width: 7%;">
                        <col span="1" style="width: 7%;">
                    </colgroup>
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('admin.pages.dialogs.opponent_name') }}</th>
                        <th>{{ __('admin.pages.dialogs.last_message') }}</th>
                        <th>{{ __('admin.labels.updated_at') }}</th>
                        <th>{{ __('admin.labels.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->opponent()->name ?? '' }}</td>
                            <td>{{ json_decode('"'.str_replace('"','\"',$item->lastMessageText()).'"') }}</td>
                            <td>{{\App\Extensions\FormatDate::formatDate($item->created_at->format("d.m.Y, H:i"))}}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="/{{$lang}}/admin/dialogs/opponent-{{ $item->opponent()->id }}"
                                       title="{{ __('admin.labels.view') }}"
                                       class="icon-btn icon-btn--yellow icon-eye"></a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif

            <div class="text-right">
                {{ $items->links('vendor.pagination.bootstrap') }}
            </div>
        </div>
    </div>

    @include('admin.v2.partials.modals.form_delete')
@endsection

@section('scripts')

@endsection
