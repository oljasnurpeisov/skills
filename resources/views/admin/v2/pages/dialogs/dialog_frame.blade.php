@extends('admin.v2.layout.course.template')

@section('content')
    <div class="container">
        <h1 class="title-primary">{{$item->opponent()->name}}</h1>
        <div class="row">
            <div class="col-md-8">
                <div class="dialog">
                    <div class="dialog__body">
                        @if(count($messages) > 0)
                            @foreach($messages as $message)
                                <div class="message green">
                                    <div class="message__avatar">
                                        @if($message->sender_id == Auth::user()->id)
                                            @if(Auth::user()->roles()->first()->slug == 'author')
                                                <img src="{{Auth::user()->author_info->avatar ?? '/assets/img/author-thumbnail.png'}}"
                                                     alt="">
                                            @elseif(Auth::user()->roles()->first()->slug == 'student')
                                                <img src="{{Auth::user()->student_info->avatar ?? '/assets/img/author-thumbnail.png'}}"
                                                     alt="">
                                            @elseif(Auth::user()->roles()->first()->slug == 'tech_support')
                                                <span>{{__('default.pages.dialogs.tech_support_avatar_title')}}</span>
                                            @endif
                                        @else
                                            @if(\App\Models\User::whereId($message->sender_id)->first()->roles()->first()->slug == 'tech_support')
                                                <span>{{__('default.pages.dialogs.tech_support_avatar_title')}}</span>
                                            @else
                                                <img src="{{$item->opponent()->avatar ?? '/assets/img/author-thumbnail.png'}}"
                                                     alt="">
                                            @endif
                                        @endif
                                    </div>
                                    <div class="message__desc">
                                        <div class="message__text">{{ json_decode('"'.str_replace('"','\"',$message->message).'"') }}</div>
                                    </div>
                                    <div class="message__date">
                                        {{\App\Extensions\FormatDate::formatDate($message->created_at->format("d.m.Y, H:i"))}}
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="dialog__empty-message">
                                {{__('default.pages.dialogs.empty_dialog')}}
                            </div>
                        @endif
                    </div>
                    <form class="dialog__sender" action="/{{$lang}}/admin/dialog-{{$item->id}}/message/create"
                          method="POST">
                        @csrf
                        <input type="text" name="message" class="input-regular"
                               placeholder="{{__('default.pages.dialogs.message_text_placeholder')}}"
                               required>
                        <button type="submit" class="btn-icon icon-send"
                                title="{{__('default.pages.dialogs.message_send_button')}}"></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!--Only this page's scripts-->

    <!---->
@endsection

