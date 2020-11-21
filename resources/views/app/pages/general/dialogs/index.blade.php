@extends('app.layout.default.template')

@section('content')
    <main class="main">

        <section class="plain">
            <div class="container">
                <h1 class="title-primary">{{__('default.pages.dialogs.title')}}</h1>
                <div class="row">
                    <div class="col-md-8">
                        <div>
                            @foreach($items as $item)
                                <a href="/{{ $lang }}/dialog/opponent-{{ $item->opponent()->id }}"
                                   title="{{__('default.pages.dialogs.open_dialog')}}"
                                   class="dialog-item dialog-item--tech-support">
                                    <div class="dialog-item__avatar">
                                        <img src="{{$item->opponent()->avatar ?? '/assets/img/author-thumbnail.png'}}" alt="">
                                    </div>
                                    <div class="dialog-item__desc">
                                        <h4 class="dialog-item__name">{{$item->opponent()->name}}</h4>
                                        <div class="dialog-item__text">
                                            {{ json_decode('"'.str_replace('"','\"',$item->lastMessageText()).'"') }}
                                        </div>
                                    </div>
                                    <div class="dialog-item__date">{{\App\Extensions\FormatDate::formatDate($item->created_at->format("d.m.Y, H:i"))}}</div>
                                </a>
                            @endforeach
                        </div>
                        <div class="text-center">
                            {{ $items->appends(request()->input())->links('vendor.pagination.default') }}
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>
@endsection

@section('scripts')
    <!--Only this page's scripts-->

    <!---->
@endsection

