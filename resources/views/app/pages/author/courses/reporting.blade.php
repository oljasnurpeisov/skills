@extends('app.layout.default.template')

@section('content')
    <main class="main">
        <section class="plain">
            <div class="container container--fluid">
                <h1 class="title-primary">{{__('default.pages.reporting.title')}}</h1>
                <form action="/{{$lang}}/my-courses/reporting">
                    <div class="row">
                        <div class="col-md-3 col-sm-4">
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.reporting.date_from_title')}}
                                    :</label>
                                <div class="input-group">
                                    <input type="text" name="date_from" placeholder=""
                                           class="input-regular custom-datepicker" value="{{$from}}"
                                           id="date_from" autocomplete="off">
                                    <i class="icon-calendar"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-4">
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.reporting.date_to_title')}}
                                    :</label>
                                <div class="input-group">
                                    <input type="text" name="date_to" placeholder=""
                                           class="input-regular custom-datepicker" value="{{$to}}" id="date_to"
                                           autocomplete="off">
                                    <i class="icon-calendar"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="form-group">
                                <label class="form-group__label">&nbsp;</label>
                                <div class="input-group">
                                    <label class="checkbox" style="margin-top: .5em;padding-right: 1em;"><input
                                                type="checkbox" name="all_time" id="all_time"
                                                value="true" {{  ($all_time ? ' checked' : '') }}><span>{{__('default.pages.reporting.date_all_title')}}</span></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <label class="form-group__label">&nbsp;</label>
                            <div class="input-group">
                                <button type="submit" title="{{__('default.pages.reporting.apply_title')}}"
                                        class="btn">{{__('default.pages.reporting.apply_title')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="scroll-x">
                <div class="scroll-x__inner">
                    <table class="report">
                        <thead>
                        <tr>
                            <th style="min-width: 200px;">{{__('default.pages.reporting.course_name')}}</th>
                            <th style="min-width: 230px;">{{__('default.pages.reporting.skills')}}</th>
                            <th style="min-width: 230px;">{{__('default.pages.reporting.professions_group')}}</th>
                            <th style="min-width: 33px;">{{__('default.pages.reporting.course_rate')}}</th>
                            <th style="min-width: 96px;">{{__('default.pages.reporting.course_status')}}</th>
                            <th style="min-width: 96px;">{{__('default.pages.reporting.course_type')}}</th>
                            <th style="min-width: 96px;">{{__('default.pages.reporting.course_cost')}}</th>
                            <th style="min-width: 33px;">{{__('default.pages.reporting.is_quota')}}</th>
                            <th style="min-width: 96px;">{{__('default.pages.reporting.cost_by_quota')}}</th>
                            <th style="min-width: 96px;">{{__('default.pages.reporting.members_free')}}</th>
                            <th style="min-width: 96px;">{{__('default.pages.reporting.certificate_free')}}</th>
                            <th style="min-width: 96px;">{{__('default.pages.reporting.qualificated_free')}}</th>
                            <th style="min-width: 96px;">{{__('default.pages.reporting.members_paid')}}</th>
                            <th style="min-width: 96px;">{{__('default.pages.reporting.certificate_paid')}}</th>
                            <th style="min-width: 96px;">{{__('default.pages.reporting.qualificated_paid')}}</th>
                            <th style="min-width: 96px;">{{__('default.pages.reporting.total_get_paid')}}</th>
                            <th style="min-width: 96px;">{{__('default.pages.reporting.members_quota')}}</th>
                            <th style="min-width: 96px;">{{__('default.pages.reporting.certificate_quota')}}</th>
                            <th style="min-width: 96px;">{{__('default.pages.reporting.qualificated_quota')}}</th>
                            <th style="min-width: 96px;">{{__('default.pages.reporting.total_get_quota')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($items as $key => $item)
                            <tr>
                                <td>{{$item->name}}</td>
                                <td>{{implode(', ', array_filter($item->skills->pluck('name_'.$lang)->toArray())) ?: implode(', ', $item->skills->pluck('name_ru')->toArray())}}</td>
                                @if(count($item->professionsBySkills()->pluck('id')->toArray())<= 0)
                                    <td>-</td>
                                @else
                                    <td>{{implode(', ', array_filter($item->professionsBySkills()->pluck('name_'.$lang)->toArray())) ?: implode(', ', array_filter($item->professionsBySkills()->pluck('name_ru')->toArray()))}}</td>
                                @endif
                                <td>{{round($item->rate->pluck('rate')->avg() ?? 0, 1)}}</td>
                                <td>{{__('default.pages.reporting.statuses.'.$item->status)}}</td>
                                <td>{{$item->is_paid == true ? __('default.pages.reporting.paid_course') : __('default.pages.reporting.free_course')}}</td>
                                <td>{{number_format($item->cost, 0, ',', ' ')}}</td>
                                <td>{{$item->quota_status == 2 ? __('default.yes_title') : __('default.no_title')}}</td>
                                @if(!empty($item->quotaCost->last()->cost))
                                    <td>{{number_format($item->quotaCost->last()->cost, 0, ',', ' ')}}</td>
                                @else
                                    <td>-</td>
                                @endif
                                <td>{{$item->course_members->where('paid_status', '=', 3)->count()}}</td>
                                <td>{{$item->course_members->where('paid_status', '=', 3)->where('is_finished', '=', true)->count()}}</td>
                                <td>{{$item->course_members->where('paid_status', '=', 3)->where('is_qualificated', '=', true)->count()}}</td>
                                <td>{{$item->course_members->where('paid_status', '=', 1)->count()}}</td>
                                <td>{{$item->course_members->where('paid_status', '=', 1)->where('is_finished', '=', true)->count()}}</td>
                                <td>{{$item->course_members->where('paid_status', '=', 1)->where('is_qualificated', '=', true)->count()}}</td>
                                <td>{{number_format($item->course_members->where('paid_status', '=', 1)->sum('payment.amount'), 0, ',', ' ')}}</td>
                                <td>{{$item->course_members->where('paid_status', '=', 2)->count()}}</td>
                                <td>{{$item->course_members->where('paid_status', '=', 2)->where('is_finished', '=', true)->count()}}</td>
                                <td>{{$item->course_members->where('paid_status', '=', 2)->where('is_qualificated', '=', true)->count()}}</td>
                                <td>{{number_format($item->course_members->where('paid_status', '=', 2)->sum('payment.amount'), 0, ',', ' ')}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="container container--fluid">
                <div class="text-right">
                    <label class="form-group__label">&nbsp;</label>
                    <div class="input-group">
                        <a href="/{{$lang}}/export-reporting" title="{{__('default.pages.reporting.export')}}"
                           class="btn small">{{__('default.pages.reporting.export')}}</a>
                    </div>
                </div>
                <div class="text-center">
                    {{ $items->links('vendor.pagination.default') }}
                </div>
            </div>
        </section>
    </main>
@endsection

@section('scripts')
    <!--Only this page's scripts-->
    <script>
        $('#all_time').change(function () {
            if ($(this).is(':checked')) {
                $('#date_from').prop('disabled', true);
                $('#date_to').prop('disabled', true);
            } else {
                $('#date_from').prop('disabled', false);
                $('#date_to').prop('disabled', false);
            }
        });

        if ($('#all_time').is(':checked')) {
            $('#date_from').prop('disabled', true);
            $('#date_to').prop('disabled', true);
        } else {
            $('#date_from').prop('disabled', false);
            $('#date_to').prop('disabled', false);
        }
    </script>
    <!---->
@endsection

