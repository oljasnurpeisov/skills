@extends('admin.v2.layout.default.template')

@section('title',__('admin.pages.reports.title').' | '.__('admin.site_name'))

@section('content')
    <div class="container container-fluid">
        <ul class="breadcrumbs">
            <li class="active">{{ __('admin.pages.reports.students_report') }}</li>
        </ul>
        @include('admin.v2.partials.components.warning')

        @include('admin.v2.partials.components.errors')

        <form class="block">
            <div class="input-group">
                <label class="input-group__title">Поиск по ФИО</label>
                <input type="text" name="student_name" placeholder="" class="input-regular"
                       value="{{$request->student_name}}">
            </div>
            <div class="collapse-block collapsed" style="display: none;" id="collapse1">
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <label class="input-group__title">ИИН</label>
                            <label class="">
                                <input type="text" name="iin"
                                       value="{{$request->iin}}" placeholder=""
                                       class="input-regular" autocomplete="off">
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input-group">
                            <label class="input-group__title">Тип оплаты за курс</label>
                            <select name="payment_type" class="input-regular chosen" data-placeholder=" ">
                                <option value="" selected hidden></option>
                                <option value="1"{{$request->payment_type == 1 ? 'selected' : ''}}>Оплачен
                                </option>
                                <option value="2"{{$request->payment_type == 2 ? 'selected' : ''}}>Приобретен по квоте
                                </option>
                                <option value="3"{{$request->payment_type == 4 ? 'selected' : ''}}>Приобретен
                                    бесплатно
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <label class="input-group__title">Дата выдачи: от</label>
                                    <label class="">
                                        <input type="text" data-date-format="dd.mm.yyyy" name="date_from"
                                               value="{{$request->date_from}}" placeholder=""
                                               class="input-regular custom-datepicker" autocomplete="off">
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <label class="input-group__title">до</label>
                                    <label class="">
                                        <input type="text" data-date-format="dd.mm.yyyy" name="date_to"
                                               value="{{$request->date_to}}" placeholder=""
                                               class="input-regular custom-datepicker" autocomplete="off">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="input-group">
                <a href="javascript:;" title="Расширенный фильтр" class="grey-link small collapse-btn"
                   data-target="collapse1">Расширенный фильтр</a></div>
            <div class="buttons">
                <div>
                    <button type="submit" class="btn btn--green">Искать</button>
                </div>
                <div>
                    <a href="/{{$lang}}/admin/reports/certificates" class="btn btn--yellow">Сбросить</a>
                </div>
                <div>
                    <a href="/{{$lang}}/admin/export-certificates-report" class="btn btn--blue">Экспорт</a>
                </div>
            </div>
        </form>

        <div class="block">
            <h2 class="title-secondary">{{__('admin.pages.reports.certificates_report')}}</h2>
            <table class="table records">
                <colgroup>
                    <col span="1" style="width: 20%;">
                    <col span="1" style="width: 20%;">
                    <col span="1" style="width: 20%;">
                    <col span="1" style="width: 20%;">
                </colgroup>
                <thead>
                <tr>
                    <th>ID Курса</th>
                    <th>{{__('admin.pages.reports.name_student')}}</th>
                    <th>Дата выдачи</th>
                    <th>Тип оплаты за курс</th>
                    <th>Сертификаты</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{$item->course_id}}</td>
                        <td>{{$item->students->student_info->name}}</td>
                        <td>{{ date('d.m.Y', strtotime($item->created_at)) }}</td>
                        <td>{{$item->payment_type == 1 ? 'Оплачен' : ($item->payment_type == 2 ? 'Приобретен по квоте' : 'Приобретен бесплатно')}}</td>
                        <td>
                            @if($item->pdf_kk != null)
                                @if(is_file(public_path($item->pdf_kk)))
                                    <a href="{{$item->pdf_kk}}"
                                       title="Қаз" target="_blank">Қаз</a>
                                @endif
                                @if(is_file(public_path($item->pdf_ru)))
                                    <a href="{{$item->pdf_ru}}"
                                       title="Рус"
                                       style="margin-left: 15px" target="_blank">Рус</a>
                                @endif
                            @else
                                @if(is_file(public_path($item->pdf_ru)))
                                    <a href="{{$item->pdf_ru}}"
                                       title="Просмотр"
                                       style="margin-left: 15px" target="_blank">Просмотр</a>
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="text-right">
                {{ $items->appends([])->links('vendor.pagination.bootstrap') }}
            </div>
        </div>
    </div>

@endsection

@section('scripts')

@endsection
