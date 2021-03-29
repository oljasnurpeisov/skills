@extends('admin.v2.layout.default.template')

@section('title',__('default.pages.video_record.title').' '.$item->id.' | '.__('default.site_name'))

@section('head')
    <style>
        .block video {
            width: 100%;
        }
    </style>
@endsection

@section('content')
    <div class="container container-fluid">
        <ul class="breadcrumbs">
            <li><a href="/admin/video_record/index">{{ __('default.pages.video_records.title') }}</a>
            </li>
            <li class="active">{{ __('default.pages.video_record.title').' '.$item->id }}</li>
        </ul>
        @include('admin.v2.partials.components.warning')
        @include('admin.v2.partials.components.errors')
        <div class="block">
            <h2 class="title-secondary">{{ __('default.pages.video_record.title') }}</h2>
            <div class="input-group">
                <label class="input-group__title"
                       for="fridge_id">{{ __('default.pages.fridge.title') }}</label>
                <input type="text" id="fridge_id"
                       value="{{ $item->fridge ? $item->fridge->uid.' | '.$item->fridge->address : '-'  }}"
                       class="input-regular" disabled>
            </div>
            <div class="input-group">
                <label class="input-group__title" for="user_id">{{ __('default.pages.user.title') }}</label>
                <input type="text" id="user_id" value="{{  $item->user ? $item->user->name : '-' }}"
                       class="input-regular" disabled>
            </div>
        </div>
        @if($user->can('admin.video_records_f'))
            <form action="/admin/video_record/{{ $item->id }}" method="post"
                  enctype="multipart/form-data" class="block">
                {{ csrf_field() }}
                <input type="hidden" name="recognition_type" value="video_f">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="title-secondary">{{ __('default.pages.video_record.video_f') }}</h2>
                        <video controls preload="auto">
                            <source src="{{ env('APP_URL').$item->video_f }}" type="video/mp4">
                            Your browser does not support HTML5 video.
                        </video>
                    </div>
                    <div class="col-md-6">
                        <h2 class="title-secondary">{{ __('default.pages.dishes.title') }}</h2>
                        <table class="table records">
                            <colgroup>
                                <col span="1" style="width: 60%;">
                                <col span="1" style="width: 30%;">
                                <col span="1" style="width: 10%;">
                            </colgroup>
                            <thead>
                            <tr>
                                <th>{{ __('default.pages.dish.name') }}</th>
                                <th>{{ __('default.pages.dish.count') }}</th>
                                <th>{{ __('default.labels.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody id="dishes-from-video-f">
                            @foreach($videoFDishes as $videoFDish)
                                <tr>
                                    <td>

                                        <input type="text" class="input-regular"
                                               value="{{ $videoFDish->dish->name }}"
                                               disabled>
                                    </td>
                                    <td>
                                        <input type="number" class="input-regular" value="{{ $videoFDish->count }}"
                                               disabled>
                                    </td>
                                    <td>

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="buttons">
                            @if($item->status === 1)
                                <div>
                                <span class="btn btn--blue"
                                      onclick="addRecognizedDish('#dishes-from-video-f');">{{ __('default.pages.video_record.add_dish') }}</span>
                                </div>
                                <div>
                                    <button type="submit"
                                            class="btn btn--green">{{ __('default.labels.save') }}</button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        @endif
        @if($user->can('admin.video_records_s'))
            <form action="/admin/video_record/{{ $item->id }}" method="post"
                  enctype="multipart/form-data" class="block">
                {{ csrf_field() }}
                <input type="hidden" name="recognition_type" value="video_s">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="title-secondary">{{ __('default.pages.video_record.video_s') }}</h2>
                        <video controls preload="auto">
                            <source src="{{ env('APP_URL').$item->video_s }}" type="video/mp4">
                            Your browser does not support HTML5 video.
                        </video>
                    </div>
                    <div class="col-md-6">
                        <h2 class="title-secondary">{{ __('default.pages.dishes.title') }}</h2>
                        <table class="table records">
                            <colgroup>
                                <col span="1" style="width: 60%;">
                                <col span="1" style="width: 30%;">
                                <col span="1" style="width: 10%;">
                            </colgroup>
                            <thead>
                            <tr>
                                <th>{{ __('default.pages.dish.name') }}</th>
                                <th>{{ __('default.pages.dish.count') }}</th>
                                <th>{{ __('default.labels.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody id="dishes-from-video-s">
                            @foreach($videoSDishes as $videoSDish)
                                <tr>
                                    <td>

                                        <input type="text" class="input-regular"
                                               value="{{ $videoSDish->dish->name }}"
                                               disabled>
                                    </td>
                                    <td>
                                        <input type="number" class="input-regular" value="{{ $videoSDish->count }}"
                                               disabled>
                                    </td>
                                    <td>

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="buttons">
                            @if($item->status === 2)
                                <div>
                                <span class="btn btn--blue"
                                      onclick="addRecognizedDish('#dishes-from-video-s');">{{ __('default.pages.video_record.add_dish') }}</span>
                                </div>
                                <div>
                                    <button type="submit"
                                            class="btn btn--green">{{ __('default.labels.save') }}</button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        @endif
    </div>

    <div class="hidden">
        <table>
            <tr id="recognized-dish">
                <td>
                    <select name="recognitions[dishes][]" class="input-regular" required>
                        <option value="" selected disabled>-</option>
                        @foreach($dishes as $fridgeDish)
                            <option value="{{ $fridgeDish->dish_id }}"
                                    data-available-count="{{ $fridgeDish->count }}">{{ $fridgeDish->dish->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" name="recognitions[count][]" placeholder="0" class="input-regular" required>
                </td>
                <td>
                    <div class="action-buttons">
                        <span class="icon-btn icon-btn--pink icon-delete"></span>
                    </div>
                </td>
            </tr>
        </table>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(".icon-delete").each(function () {
            $(this).click(function () {
                $(this).closest('div').closest('td').closest('tr').remove();
            });
        });

        function addRecognizedDish(target) {
            var element = $("#recognized-dish").clone();
            element.removeAttr('id');
            element.find('select').addClass('chosen');
            element.find('select').change(function () {
                var option = element.find('select option:selected'),
                    count = option.attr("data-available-count"),
                    input = element.find('input[type=number]');

                input.attr('max', count);
                if (input.val() > count) {
                    input.val(count);
                }
            });

            element.find('.icon-delete').click(function () {
                element.remove();
            });
            $(target).append(element);
            chosenInit();
        }
    </script>
@endsection
