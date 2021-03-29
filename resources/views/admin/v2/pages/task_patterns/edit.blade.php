@extends('admin.v2.layout.default.template')

@section('title',$item->name.' | '.__('default.site_name'))

@section('content')
    <div class="container container-fluid">
        <ul class="breadcrumbs">
            <li><a href="/admin/task/pattern/index">{{ __('default.pages.task_patterns.title') }}</a>
            </li>
            <li class="active">{{ $item->name }}</li>
        </ul>
        @include('admin.v2.partials.components.warning')
        @include('admin.v2.partials.components.errors')
        <form action="/admin/task/pattern/{{ $item->id }}" method="post"
              enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="block">
                <h2 class="title-secondary">{{ __('default.pages.task_pattern.title') }}</h2>
                <div class="input-group {{ $errors->has('name') ? ' has-error' : '' }}">
                    <label class="input-group__title">{{ __('default.pages.task_pattern.name') }} *</label>
                    <input type="text" name="name" value="{{ $item->name }}"
                           placeholder="{{ __('default.labels.fill_field',['field' => __('default.pages.task_pattern.name')]) }}"
                           class="input-regular" required>
                    @if ($errors->has('name'))
                        <span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row" style="margin-bottom: 1.875rem;">
                <div class="col-md-6">
                    <div class="block">
                        <h2 class="title-secondary">{{ __('default.pages.task_pattern.out') }}</h2>
                        <table class="table records">
                            <colgroup>
                                <col span="1" style="width: 60%;">
                                <col span="1" style="width: 20%;">
                                <col span="1" style="width: 20%;">
                            </colgroup>
                            <thead>
                            <tr>
                                <th>{{ __('default.pages.dish.name') }}</th>
                                <th>{{ __('default.pages.task_pattern.count') }}</th>
                                <th>{{ __('default.labels.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody id="dishes-to-unload">
                            @foreach($item->dishes as $key => $dish)
                                @if($dish->pivot->type === 'unload')
                                    <tr>
                                        <td>
                                            <select name="dishes[unload][ids][]" class="input-regular chosen" required>
                                                <option value="" selected disabled>-</option>
                                                @foreach($dishes as $d)
                                                    <option value="{{ $d->id }}"
                                                            @if($d->id === $dish->id) selected @endif>{{ $d->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="dishes[unload][count][]" placeholder="0"
                                                   class="input-regular" disabled>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <span class="icon-btn icon-btn--pink icon-delete"></span>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                        <span class="btn btn--blue"
                              onclick="addDishToUnload();">{{ __('default.pages.task_pattern.add_dish_for_unload') }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="block">
                        <h2 class="title-secondary">{{ __('default.pages.task_pattern.in') }}</h2>
                        <table class="table records">
                            <colgroup>
                                <col span="1" style="width: 60%;">
                                <col span="1" style="width: 20%;">
                                <col span="1" style="width: 20%;">
                            </colgroup>
                            <thead>
                            <tr>
                                <th>{{ __('default.pages.dish.name') }}</th>
                                <th>{{ __('default.pages.task_pattern.count') }}</th>
                                <th>{{ __('default.labels.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody id="dishes-to-load">
                            @foreach($item->dishes as $key => $dish)
                                @if($dish->pivot->type === 'load')
                                    <tr>
                                        <td>
                                            <select name="dishes[load][ids][]" class="input-regular chosen" required>
                                                <option value="" selected disabled>-</option>
                                                @foreach($dishes as $d)
                                                    <option value="{{ $d->id }}"
                                                            @if($d->id === $dish->id) selected @endif>{{ $d->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="dishes[load][count][]" placeholder="0"
                                                   value="{{ $dish->pivot->count }}"
                                                   class="input-regular" required>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <span class="icon-btn icon-btn--pink icon-delete"></span>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                        <span class="btn btn--blue"
                              onclick="addDishToLoad();">{{ __('default.pages.task_pattern.add_dish_for_load') }}</span>
                    </div>
                </div>
            </div>
            <div class="block">
                <div class="buttons">
                    <div>
                        <button type="submit" class="btn btn--green">{{ __('default.labels.save') }}</button>
                    </div>
                    <div>
                        <a href="/admin/task/pattern/{{ $item->id }}" class="btn btn--red btn--delete">
                            {{ __('default.pages.deleting.submit') }}
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="hidden">
        <table>
            <tr id="dish-to-unload-component">
                <td>
                    <select name="dishes[unload][ids][]" class="input-regular" required>
                        <option value="" selected disabled>-</option>
                        @foreach($dishes as $dish)
                            <option value="{{ $dish->id }}">{{ $dish->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" name="dishes[unload][count][]" placeholder="0" class="input-regular" disabled>
                </td>
                <td>
                    <div class="action-buttons">
                        <span class="icon-btn icon-btn--pink icon-delete"></span>
                    </div>
                </td>
            </tr>
            <tr id="dish-to-load-component">
                <td>
                    <select name="dishes[load][ids][]" class="input-regular" required>
                        <option value="" selected disabled>-</option>
                        @foreach($dishes as $dish)
                            <option value="{{ $dish->id }}">{{ $dish->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" name="dishes[load][count][]" placeholder="0" class="input-regular" required>
                </td>
                <td>
                    <div class="action-buttons">
                        <span class="icon-btn icon-btn--pink icon-delete"></span>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    @include('admin.v2.partials.modals.form_delete')
@endsection

@section('scripts')
    <script type="text/javascript">
        $(".icon-delete", "#dishes-to-unload").each(function () {
            $(this).click(function () {
                $(this).closest('div').closest('td').closest('tr').remove();
            });
        });

        $(".icon-delete", "#dishes-to-load").each(function () {
            $(this).click(function () {
                $(this).closest('div').closest('td').closest('tr').remove();
            });
        });

        function addDishToUnload() {
            var element = $("#dish-to-unload-component").clone();
            element.removeAttr('id');
            element.find('select').addClass('chosen');
            element.find('.icon-delete').click(function (e) {
                element.remove();
            });
            element.appendTo("#dishes-to-unload");
            chosenInit();
        }

        function addDishToLoad() {
            var element = $("#dish-to-load-component").clone();
            element.removeAttr('id');
            element.find('select').addClass('chosen');
            element.find('.icon-delete').click(function (e) {
                element.remove();
            });
            element.appendTo("#dishes-to-load");
            chosenInit();
        }
    </script>
@endsection
