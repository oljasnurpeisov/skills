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
                                                {!!trans($notification->name, ['course_name' => '"'.$notification->course->name.'"', 'lang' => $lang, 'course_id' => $notification->course->id, 'notification_id' => $notification->id])!!}
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
                                    @if (Auth::user()->hasRole('author'))
                                        <div id="rulesQuotaModal{{optional($notification->course)->id.'-'.$notification->id}}" style="display:none; width: 500px" class="modal-form">
                                            <h4 class="title-primary text-center">{{__('notifications.quota_rules_title')}}</h4>
                                            <div class="plain-text" style="font-size: 1em">
                                                {!! trans(__('notifications.quota_rules_description'), ['course_id' => optional($notification->course)->id,'course_name' => optional($notification->course)->name, 'author_name' => Auth::user()->author_info->name . ' ' . Auth::user()->author_info->surname, 'course_quota_cost' => json_decode($notification->data)[0]->course_quota_cost ?? 0, 'lang' => $lang])!!}
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="notification">
                                        <div class="notification__text">
                                            @php($opponent = \App\Models\User::whereId(json_decode($notification->data)[0]->dialog_opponent_id ?? 0)->first())
                                            {!!trans($notification->name, ['course_name' => '"'. optional($notification->course)->name .'"', 'lang' => $lang, 'course_id' => optional($notification->course)->id, 'opponent_id' => json_decode($notification->data)[0]->dialog_opponent_id ?? 0, 'reject_message' => json_decode($notification->data)[0]->course_reject_message ?? '','user_name' => $opponent ? ($opponent->hasRole('author') ? $opponent->author_info->name . ' ' . $opponent->author_info->surname : $opponent->student_info->name ??  $opponent->name) : '', 'course_quota_cost' => json_decode($notification->data)[0]->course_quota_cost ?? 0])!!}
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

