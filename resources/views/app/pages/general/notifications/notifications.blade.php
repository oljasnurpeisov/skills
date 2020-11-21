@extends('app.layout.default.template')

@section('content')
    <main class="main">


        <section class="plain">
            <div class="container">
                <h1 class="title-primary">{{__('notifications.title')}}</h1>
                <div class="row">
                    <div class="col-md-8">
                        <div>
                            @foreach($notifications as $notification)
                                <div class="notification">
                                    <div class="notification__text">
                                        {!!trans($notification->name, ['course_name' => '"'.$notification->course->name.'"'])!!}
                                    </div>
                                    <div class="notification__date">{{\App\Extensions\FormatDate::formatDate($notification->created_at->format("d.m.Y, H:i"))}}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    {{ $notifications->appends(request()->input())->links('vendor.pagination.default') }}
                </div>
            </div>
        </section>

    </main>
@endsection

@section('scripts')
    <!--Only this page's scripts-->

    <!---->
@endsection

