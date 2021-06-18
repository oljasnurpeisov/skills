@extends('admin.v2.layout.default.template')

@section('title', $title)

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
                            <a href="{{ route('admin.routes.create', ['lang' => $lang, 'type' => $type]) }}" class="btn">Добавить</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('admin.v2.partials.components.warning')

        <div class="block">
            <h2 class="title-secondary">{{ __('admin.labels.record_list') }}</h2>

            <table class="table records">
                <colgroup>
                    <col span="1">
                    <col span="1">
                    <col span="1" style="width: 5%;">
                </colgroup>
                <thead>
                <tr>
                    <th>Роль</th>
                    <th>Сортировка</th>
                    <th>Управление</th>
                </tr>
                </thead>
                <tbody>
                @foreach($routes as $route)
                    <tr>
                        <td>{{ $route->role->name }}</td>
                        <td>{{ $route->sort }}</td>
                        <td>
                            <a href="{{ route('admin.routes.role.edit', ['lang' => $lang, 'type' => $type, 'route_id' => $route->id]) }}" title="" class="icon-btn icon-btn--yellow icon-edit"></a>
                            <form method="POST" action="{{ URL::route('admin.routes.destroy', ['lang' => $lang, 'type' => $type, 'route_id' => $route->id]) }}" style="display: inline-block; vertical-align: 1px;">
                                {!! csrf_field() !!}
                                <input type="hidden" name="_method" value="delete">
                                <button class="icon-btn icon-btn--yellow icon-delete" title="Удалить" onclick="if(confirm('Вы уверены?'))submit();else return false;" style="display: inline; border: none;"></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="text-right">
                {{ $routes->links('vendor.pagination.bootstrap') }}
            </div>
        </div>
    </div>

    @include('admin.v2.partials.modals.form_delete')
@endsection

@section('scripts')

@endsection
