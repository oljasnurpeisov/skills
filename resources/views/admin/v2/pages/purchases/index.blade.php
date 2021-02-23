@extends('admin.v2.layout.default.template')

@section('title',__('default.pages.purchases.title').' | '.__('default.site_name'))

@section('head')

@endsection

@section('content')
    <div class="container container-fluid">
        <div class="title-block">
            <div class="row row--multiline align-items-center">
                <div class="col-md-4">
                    <h1 class="title-primary" style="margin-bottom: 0">{{ __('default.pages.purchases.title')  }}</h1>
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
                        <col span="1" style="width: 15%;">
                        <col span="1" style="width: 20%;">
                        <col span="1" style="width: 20%;">
                        <col span="1" style="width: 15%;">
                        <col span="1" style="width: 10%;">
                    </colgroup>
                    <thead>
                    <tr>
                        <th>#</th>
                        <td>{{ __('default.pages.user.title') }}</td>
                        <td>{{ __('default.pages.fridge.title') }}</td>
                        <td>{{ __('default.pages.purchase.dishes') }}</td>
                        <td>{{ __('default.pages.purchase.cost') }}</td>
                        <td>{{ __('default.pages.purchase.sum') }}</td>
                        <th>{{ __('default.labels.created_at') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>
                                <a href="/admin/user/{{ $item->user_id }}">{{ $item->user->surname. ' ' .$item->user->name }}</a>
                            </td>
                            <td><a href="/admin/fridge/{{ $item->fridge_id }}">{{ $item->fridge->address }}</a>
                            </td>
                            <td>
                                @foreach($item->dishes() as $dish)
                                    {{ $dish['name'].' x '.$dish['count'] }}
                                    <br/>
                                @endforeach
                            </td>
                            <td>
                                @foreach($item->dishes() as $dish)
                                    {{ $dish['cost'].' x '.$dish['count'].' = '.$dish['cost'] * $dish['count'].' '. __('default.pages.purchase.tg') }}
                                    <br/>
                                @endforeach
                            </td>
                            <td>{{ $item->sum .' '. __('default.pages.purchase.tenge') }}</td>
                            <td>{!! $item->created_at !!}</td>
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
