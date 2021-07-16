@extends('app.layout.default.template')

@section('content')
    <main class="main">
        <section class="plain">
            <div class="container">
                <div class="row row--multiline column-reverse-sm">
                    <div class="col-md-12">

                        @if (pathinfo($contract->link)['extension'] === 'pdf')
                            <object data="{{ route('author.contracts.pdf', ['lang' => 'ru', 'contract_id' => $contract->id]) }}" type="application/pdf" internalinstanceid="3" title="" width="100%" height="600"></object>
                        @else
                            <iframe src="{{ route('author.contracts.doc', ['lang' => 'ru', 'contract_id' => $contract->id]) }}" frameborder="0" width="100%" height="600"></iframe>
                        @endif

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
