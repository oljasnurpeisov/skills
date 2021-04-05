@extends('app.layout.default.template')

@section('content')
    <main class="main">


        <section class="plain">
            <div class="container">
                <h1 class="title-primary">{{__('default.pages.profile.my_certificates')}}</h1>

                <div class="row row--multiline">
                    @foreach($items as $certificate)
                        <div class="col-md-3 col-sm-4 col-xs-6">
                            <div class="certificate">
                                <div class="certificate__overlay">
                                    <a href="{{$certificate->getAttribute('png_ru')}}" data-fancybox=""
                                       title="{{__('default.pages.courses.zoom_certificate')}}"
                                       class="icon-zoom-in"></a>
                                    <a href="{{$certificate->getAttribute('pdf_ru')}}"
                                       title="{{__('default.pages.profile.download')}}" class="icon-download"
                                       download></a>
                                </div>
                                <img src="{{$certificate->getAttribute('png_ru')}}" alt="">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

    </main>
@endsection

@section('scripts')

@endsection

