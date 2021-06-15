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

        <div class="block">
            Скачать договор: <a href="{{ asset($contract->link) }}">Скачать</a>

            <iframe src="{{ route('admin.contracts.get_contract_html', ['lang' => 'ru', 'id' => $contract->id]) }}" frameborder="0" width="100%" height="600"></iframe>

        </div>
    </div>

    @include('admin.v2.partials.modals.form_delete')
@endsection

@section('scripts')

@endsection
