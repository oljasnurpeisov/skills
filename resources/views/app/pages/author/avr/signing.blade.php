@extends('app.layout.default.template')

@section('content')
    <main class="main">
        <section class="plain">
            <div class="container">
                <div class="row row--multiline column-reverse-sm">
                    <div class="col-md-12">
                        <iframe src="{{ route('author.avr.signing.avrDoc', ['lang' => 'ru', 'avr_id' => $avr->id]) }}" frameborder="0" width="100%" height="600"></iframe>

                        <a href="{{ route('author.avr.signing.next', ['lang' => 'ru', 'avr_id' => $avr->id]) }}" class="btn">Подписать</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection


<style>
    #contract * {word-break: break-word}
    #contract table {max-width: 100% !important;}
    #contract tr {max-width: 50% !important;}
</style>
@section('scripts')
    <!--Only this page's scripts-->
@endsection

