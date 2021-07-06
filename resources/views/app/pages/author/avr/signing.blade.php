@extends('app.layout.default.template')

@section('content')
    <main class="main">
        <section class="plain">
            <div class="container">
                <div class="row row--multiline column-reverse-sm">
                    <div class="col-md-12">
                        <iframe src="{{ route('author.avr.signing.avrDoc', ['lang' => 'ru', 'avr_id' => $avr->id]) }}" frameborder="0" width="100%" height="600"></iframe>

                        @if ($avr->isSignator())
                            @if (empty($avr->invoice_link))
                                <form action="{{ route('author.avr.signing.update', ['lang' => 'ru', 'avr_id' => $avr->id]) }}">
                                    <table>
                                        <tr>
                                            <td>Номер АВР:</td>
                                            <td><input type="text" name="avr_number" class="input-regular" placeholder="Номер АВР" required></td>
                                        </tr>
                                        <tr>
                                            <td>Счет фактуры:</td>
{{--                                            <td><input type="file" name="invoice" placeholder="Счет фактуры" required></td>--}}
                                            <td>
                                                <div data-url="{{ route('ajaxUploadFile') }}" data-maxfiles="1"
                                                     data-maxsize="1" data-acceptedfiles="application/pdf"
                                                     class="dropzone-default dropzone-multiple">
                                                    <input type="text" name="invoice" value="" required>
                                                    <div class="dropzone-default__info">JPG, PNG, PDF • {{__('default.pages.profile.max_file_title') }}. 1MB</div>
                                                    <a href="javascript:;" title="{{__('default.pages.profile.choose_file')}}" class="dropzone-default__link">{{__('default.pages.profile.choose_file')}}</a>
                                                    <div class="previews-container"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td><button class="btn">Сохранить</button></td>
                                        </tr>
                                    </table>
                                </form>
                            @else
                                <td><a href="{{ route('author.avr.signing.next', ['lang' => 'ru', 'avr_id' => $avr->id]) }}" class="btn">Подписать</a></td>
                            @endif

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
@section('scripts')
    <!--Only this page's scripts-->
@endsection

