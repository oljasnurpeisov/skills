@extends('admin.v2.layout.default.template')

@section('title',' | '.__('admin.site_name'))

@section('content')
    <div class="container container-fluid">
        <ul class="breadcrumbs">
            <li><a href="/{{$lang}}/admin/dialogs">{{ __('admin.pages.dialogs.title') }}</a></li>
            <li class="active">{{ $item->opponent()->name }}</li>
        </ul>
        @include('admin.v2.partials.components.warning')
        @if(session('newPassword'))
            <div class="alert alert-warning" role="alert">
                <strong>{{ __('admin.notifications.new_password',['password' => session('newPassword')]) }}</strong>
            </div>
        @endif
        @include('admin.v2.partials.components.errors')
        <div class="block">

            <div class="active">
                <iframe id="page" src="/{{$lang}}/admin/dialogs/dialog-iframe-{{$item->opponent()->id}}" frameborder="0"
                        width="100%" height="600px"></iframe>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script type="text/javascript">
        // Selecting the iframe element
        var iframe = document.getElementById("page");

        // Adjusting the iframe height onload event
        iframe.onload = function () {
            iframe.style.height = iframe.contentWindow.document.body.scrollHeight + 'px';
        }
    </script>
@endsection
