@extends('admin.v2.layout.default.template')

@section('title',__('admin.pages.reports.title').' | '.__('admin.site_name'))

@section('content')
    <div class="container container-fluid">
        {{--        <ul class="breadcrumbs">--}}
        {{--            <li><a href="/{{$lang}}/admin/student/index">{{ __('admin.pages.students.title') }}</a></li>--}}
        {{--            <li class="active">{{ $item->email }}</li>--}}
        {{--        </ul>--}}
        {{--        @include('admin.v2.partials.components.warning')--}}
        {{--        --}}
        {{--        @include('admin.v2.partials.components.errors')--}}

        <form class="block">
            <div class="input-group">
                <label class="input-group__title">Поисковая фраза</label>
                <input type="text" name="text" placeholder="" class="input-regular">
            </div>
            <div class="collapse-block collapsed" style="display: none;" id="collapse1">
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <label class="input-group__title">Организация</label>
                            <select name="organization" class="input-regular chosen" data-placeholder=" ">
                                <option value="" selected hidden></option>
                                <option value="value1">value 1</option>
                                <option value="value2">value 2</option>
                                <option value="value3">value 3</option>
                                <option value="value4">value 4</option>
                                <option value="value5">value 5</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <label class="input-group__title">Год создания: с</label>
                                    <label class="date">
                                        <input type="text" name="creationDateFrom" placeholder=""
                                               class="input-regular custom-datepicker">
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <label class="input-group__title">до</label>
                                    <label class="date">
                                        <input type="text" name="creationDateTo" placeholder=""
                                               class="input-regular custom-datepicker">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <label class="input-group__title">Дата сдачи в архив: с</label>
                                    <label class="date">
                                        <input type="text" name="archiveDateFrom" placeholder=""
                                               class="input-regular custom-datepicker">
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <label class="input-group__title">до</label>
                                    <label class="date">
                                        <input type="text" name="archiveDateTo" placeholder=""
                                               class="input-regular custom-datepicker">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <label class="input-group__title">Дата уничтожения: с</label>
                                    <label class="date">
                                        <input type="text" name="destroyDateFrom" placeholder=""
                                               class="input-regular custom-datepicker">
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <label class="input-group__title">до</label>
                                    <label class="date">
                                        <input type="text" name="destroyDateTo" placeholder=""
                                               class="input-regular custom-datepicker">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <label class="input-group__title">Дата выдачи: с</label>
                                    <label class="date">
                                        <input type="text" name="releaseDateFrom" placeholder=""
                                               class="input-regular custom-datepicker">
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <label class="input-group__title">до</label>
                                    <label class="date">
                                        <input type="text" name="releaseDateTo" placeholder=""
                                               class="input-regular custom-datepicker">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <label class="input-group__title">&nbsp;</label>
                            <div class="input-group">
                                <label class="checkbox">
                                    <input type="checkbox" name="archived">
                                    <span>В архиве</span>
                                </label>
                            </div>
                            <div class="input-group">
                                <label class="checkbox">
                                    <input type="checkbox" name="released">
                                    <span>Выдано</span>
                                </label>
                            </div>
                            <div class="input-group">
                                <label class="checkbox">
                                    <input type="checkbox" name="storage">
                                    <span>Оперативное хранение</span>
                                </label>
                            </div>
                            <div class="input-group">
                                <label class="checkbox">
                                    <input type="checkbox" name="destroyed">
                                    <span>Уничтожено</span>
                                </label>
                            </div>
                            <div class="input-group">
                                <label class="checkbox">
                                    <input type="checkbox" name="formed">
                                    <span>Дело сформировано</span>
                                </label>
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
                    <button class="btn btn--green">Искать</button>
                </div>
                <div>
                    <button class="btn btn--yellow">Сбросить</button>
                </div>
            </div>
        </form>

        <div class="block">
            <h2 class="title-secondary">{{__('admin.pages.reports.authors_report')}}</h2>
            <table class="table records">
                <colgroup>
                    <col span="1" style="width: 20%;">
                    <col span="1" style="width: 20%;">
                    <col span="1" style="width: 20%;">
                    <col span="1" style="width: 15%;">
                    <col span="1" style="width: 10%;">
                    <col span="1" style="width: 15%;">
                </colgroup>
                <thead>
                <tr>
                    <th>{{__('admin.pages.reports.name_title')}}</th>
                    <th>{{__('admin.pages.reports.specialization')}}</th>
                    <th>{{__('admin.pages.reports.rating')}}</th>
                    <th>{{__('admin.pages.reports.courses_count')}}</th>
                    <th>{{__('admin.pages.reports.courses_paid_count')}}</th>
                    <th>{{__('admin.pages.reports.courses_free_count')}}</th>
                    <th>{{__('admin.pages.reports.courses_by_quota_count')}}</th>
                    <th>{{__('admin.pages.reports.courses_students_count')}}</th>
                    <th>{{__('admin.pages.reports.courses_certificates_students_count')}}</th>
                    <th>{{__('admin.pages.reports.courses_students_confirm_qualification_count')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{$item->author_info->name . ' ' . $item->author_info->surname}}</td>
                        <td>{{ implode(', ', json_decode($item->author_info->specialization) ?? []) }}</td>
                        <td>{{round($item->average_rates, 1)}}</td>
                        <td>{{$item->courses->count()}}</td>
                        <td>{{$item->courses->where('is_paid', '=', true)->count()}}</td>
                        <td>{{$item->courses->where('is_paid', '=', false)->count()}}</td>
                        <td>{{$item->courses->where('quota_status', '=', 2)->count()}}</td>
                        <td>{{count($item->members) ?? 0}}</td>
                        <td>{{count($item->certificate_members) ?? 0}}</td>
                        <td>{{count($item->qualification_students) ?? 0}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="text-right">
                {{ $items->links('vendor.pagination.bootstrap') }}
            </div>
        </div>
    </div>

@endsection

@section('scripts')

@endsection
