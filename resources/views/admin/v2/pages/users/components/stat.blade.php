@extends('admin.v2.partials.components.stat')

@section('stat')
    <tfoot class="gray">
    <tr>
        <td colspan="2">
            <i>{!! __('default.pages.user.stat.created',['attr' => __('default.pages.stat.all')]) !!}</i>
            <strong>{{ $created }}</strong><br/>
            <i>{!! __('default.pages.user.stat.edited',['attr' => __('default.pages.stat.all')]) !!}</i>
            <strong>{{ $edited }}</strong><br/>
            <i>{!! __('default.pages.user.stat.removed',['attr' => __('default.pages.stat.all')]) !!}</i>
            <strong>{{ $removed }}</strong>
        </td>
    </tr>
    </tfoot>
@endsection
