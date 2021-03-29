@extends('app.layout.default.template')

@section('content')
    <main class="main">


        <section class="plain big-padding">
            <div class="container">
                <h1 class="title-primary">{{__('default.pages.footer.help')}}</h1>

                <br>
                @foreach($items as $key => $item)
                    <h2 class="title-secondary">{{$item['name']}}</h2>
                    <div class="accordion-group">
                        <!--Add class .independent to wrapper for independent collapse behavior-->
                        <div class="row">
                            <div class="col-md-8">
                                @if(!empty($item['tabs']))
                                    @foreach($item['tabs'] as $tab)
                                        <div class="accordion">
                                            <div class="accordion__header">
                                                {{$tab['name']}}
                                            </div>
                                            <div class="accordion__body">
                                                <div class="plain-text">
                                                    {!! $tab['description'] !!}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

    </main>
@endsection

@section('scripts')

@endsection

