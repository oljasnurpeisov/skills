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
            </div>
        </div>

        @include('admin.v2.partials.components.warning')

        <div class="alert alert-info col-sm-12" id="signResult" style="display: none"></div>

        <div style="display: none">
            <form>
                <textarea name="xmlRequest" id="xmlRequest" class="form-control col-sm-12" rows="10"></textarea>
                <textarea name="xmlResponse" id="xmlResponse" class="form-control col-sm-12" rows="10"></textarea>
            </form>
        </div>

        <div class="block">
            <table class="table records">
                <colgroup>
                    <col span="1" style="width: 5%;">
                    <col span="1" style="width: 5%;">
                    <col span="1" style="width: 9%;">
                </colgroup>
                <thead>
                <tr>
                    <th>Дата</th>
                    <th>Номер договора</th>
                    <th>Событие</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($history as $item)
                        <tr>
                            <td>{{ $item->created_at }}</td>
                            <td>{{ $item->contract->number }}</td>
                            <td>{!! $item->comment !!}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('scripts')

@endsection
