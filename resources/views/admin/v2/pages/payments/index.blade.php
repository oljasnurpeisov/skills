@extends('admin.v2.layout.default.template')

@section('title',__('default.pages.payments.title').' | '.__('default.site_name'))

@section('head')

@endsection

@section('content')
    <div class="container container-fluid">
        <div class="title-block">
            <div class="row row--multiline align-items-center">
                <div class="col-md-4">
                    <h1 class="title-primary" style="margin-bottom: 0">{{ __('default.pages.payments.title')  }}</h1>
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
                        <col span="1" style="width: 5%;">
                        <col span="1" style="width: 15%;">
                        <col span="1" style="width: 20%;">
                        <col span="1" style="width: 15%;">
                        <col span="1" style="width: 15%;">
                        <col span="1" style="width: 10%;">
                        <col span="1" style="width: 10%;">
                        <col span="1" style="width: 10%;">
                    </colgroup>
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('default.pages.payment.amount') }}</th>
                        <th>{{ __('default.pages.user.title') }}</th>
                        <th>{{ __('default.pages.payment.source') }}</th>
                        <th>{{ __('default.pages.payment.status') }}</th>
                        <th>{{ __('default.labels.created_at') }}</th>
                        <th>{{ __('default.labels.updated_at') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->amount }}</td>
                            <td>{{ $item->user ? $item->user->name : '-' }}</td>
                            <td>{{ $item->source }}</td>
                            <td>
                                <div class="alert @if($item->published) alert-success @else alert-warning @endif"
                                     style="margin: 0;">
                                    {{ __('default.pages.payment.statuses.'.(int)$item->published) }}
                                </div>
                            </td>
                            <td>{!! $item->created_at !!}</td>
                            <td>{!! $item->updated_at !!}</td>
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
