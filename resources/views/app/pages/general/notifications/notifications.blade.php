@extends('app.layout.default.template')

@section('content')
    <main class="main">


        <section class="plain">
            <div class="container">
                <h1 class="title-primary">{{__('notifications.title')}}</h1>
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-8">
                        <div>
                            @foreach($notifications as $notification)
                                @if($notification->type == 1)
                                    <div class="notification">
                                        <div class="notification__text">
                                            <form method="POST"
                                                  action="/{{$lang}}/my-courses/quota-confirm-course/{{$notification->course->id}}"
                                                  id="quota_confirm_form">
                                                {{ csrf_field() }}
                                                {!!trans($notification->name, ['course_name' => '"'.$notification->course->name.'"'])!!}
                                                @if($notification->course->quota_status == 1)
                                                    <div class="buttons">
                                                        <button name="action" value="confirm" title="{{__('notifications.confirm_btn_title')}}"
                                                           class="btn">{{__('notifications.confirm_btn_title')}}</button>
                                                        <button name="action" value="reject" title="{{__('notifications.reject_btn_title')}}"
                                                           class="ghost-btn" style="background-color: white">{{__('notifications.reject_btn_title')}}</button>
                                                    </div>
                                                @endif
                                            </form>
                                        </div>
                                        <div class="notification__date">{{\App\Extensions\FormatDate::formatDate($notification->created_at->format("d.m.Y, H:i"))}}</div>
                                    </div>
                                @else
                                    <div class="notification">
                                        <div class="notification__text">
                                            {!!trans($notification->name, ['course_name' => '"'.$notification->course->name.'"'])!!}
                                        </div>
                                        <div class="notification__date">{{\App\Extensions\FormatDate::formatDate($notification->created_at->format("d.m.Y, H:i"))}}</div>
                                    </div>
                                @endif
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

