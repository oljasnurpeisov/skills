@extends('admin.v2.layout.default.template')

@section('title', 'Добавление роли')

@section('head')

@endsection

@section('content')
    <div class="container container-fluid">
        <div class="title-block">
            <div class="row row--multiline align-items-center">
                <div class="col-md-4">
                    <h1 class="title-primary" style="margin-bottom: 0">Добавление роли</h1>
                </div>
            </div>
        </div>

        @include('admin.v2.partials.components.warning')

        <div class="block">
            @include('admin.v2.partials.components.warning')
            @include('admin.v2.partials.components.errors')

            <form action="{{ route('admin.routes.role.update', ['lang' => $lang, 'type' => $type, 'route_id' => $route->id]) }}" method="POST">
                @csrf

                <input type="hidden" name="id" value="{{ $route->id }}">
                <input type="hidden" name="type" value="{{ $type }}">

                <div class="input-group ">
                    <label class="input-group__title">Номер в очереди *</label>
                    <input type="number" name="sort" placeholder="" class="input-regular" required="" value="{{ $route->sort ?? old('sort') }}">
                </div>

                <div class="buttons">
                    <div>
                        <button type="submit" class="btn btn--green">Сохранить</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('admin.v2.partials.modals.form_delete')
@endsection

@section('scripts')

@endsection
