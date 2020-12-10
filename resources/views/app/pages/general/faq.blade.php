@extends('app.layout.default.template')

@section('content')
    <main class="main">


        <section class="plain big-padding">
            <div class="container">
                <h1 class="title-primary">Вопросы и ответы</h1>

                <br>
                <h2 class="title-secondary">Обучение</h2>
                <div class="accordion-group"><!--Add class .independent to wrapper for independent collapse behavior-->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="accordion">
                                <div class="accordion__header">
                                    Nullam dolor in leo tellus sit malesuada mauris ante velit?
                                </div>
                                <div class="accordion__body">
                                    <div class="plain-text">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                                        incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis
                                        nostrud
                                        exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute
                                        irure
                                        dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
                                        pariatur.
                                        Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia
                                        deserunt
                                        mollit anim id est laborum
                                    </div>
                                </div>
                            </div>
                            <div class="accordion">
                                <div class="accordion__header">
                                    Volutpat condimentum vivamus natoque rutrum id tincidunt lorem quam felis?
                                </div>
                                <div class="accordion__body">
                                    <div class="plain-text">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                                        incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis
                                        nostrud
                                        exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute
                                        irure
                                        dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
                                        pariatur.
                                        Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia
                                        deserunt
                                        mollit anim id est laborum
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <h2 class="title-secondary">Авторство</h2>
                <div class="accordion-group"><!--Add class .independent to wrapper for independent collapse behavior-->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="accordion">
                                <div class="accordion__header">
                                    Nullam dolor in leo tellus sit malesuada mauris ante velit?
                                </div>
                                <div class="accordion__body">
                                    <div class="plain-text">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                                        incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis
                                        nostrud
                                        exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute
                                        irure
                                        dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
                                        pariatur.
                                        Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia
                                        deserunt
                                        mollit anim id est laborum
                                    </div>
                                </div>
                            </div>
                            <div class="accordion">
                                <div class="accordion__header">
                                    Volutpat condimentum vivamus natoque rutrum id tincidunt lorem quam felis?
                                </div>
                                <div class="accordion__body">
                                    <div class="plain-text">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                                        incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis
                                        nostrud
                                        exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute
                                        irure
                                        dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
                                        pariatur.
                                        Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia
                                        deserunt
                                        mollit anim id est laborum
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @auth
                    @php
                        $tech_support = App\Models\User::whereHas('roles', function ($q) {
            $q->where('slug', '=', 'tech_support');
        })->first();
                    @endphp
                    <a href="/{{$lang}}/dialog/opponent-{{$tech_support->id}}" title="{{__('default.pages.faq.tech_support_title')}}" class="btn">{{__('default.pages.faq.tech_support_title')}}</a>
                @else
                    <a href="#authorization" title="{{__('default.pages.faq.tech_support_title')}}" class="btn">{{__('default.pages.faq.tech_support_title')}}</a>
                @endif

            </div>
        </section>

    </main>
@endsection

@section('scripts')

@endsection

