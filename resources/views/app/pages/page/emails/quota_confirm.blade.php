<html>
<body>
<div style="background-color:#eee;background-image:none;background-repeat:repeat;background-position:top left;color:#333;font-family:Helvetica,Arial,sans-serif;line-height:1.25">
    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
        <tbody>
        <tr>
            <td align="center" valign="top">
                <table border="0" cellpadding="20" cellspacing="0" width="600">
                    <tbody>
                    <tr>
                        <td align="center" valign="top">
                            <table border="0" cellpadding="0" cellspacing="0" height="90" width="100%"
                                   style="background-color:#2D9CDB;background-image:none;background-repeat:repeat;background-position:top left">
                                <tbody>
                                <tr>
                                    <td align="center" valign="middle">
                                        <a href="{{ env('APP_URL') }}" style="font-size: 24px;color: #fff;text-decoration: none;">{{ env('APP_NAME','') }}</a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%"
                                   style="background-color:#fff;background-image:none;background-repeat:repeat;background-position:top left">
                                <tbody>
                                <tr>
                                    <td align="center" valign="top">
                                        <table border="0" cellpadding="40" cellspacing="0" height="0" width="100%">
                                            <tbody>
                                            <form method="POST" action="{{env('APP_URL')}}/ru/my-courses/quota-confirm-course/{{$data['course_id']}}"
                                                  id="quota_confirm_form">
                                            <tr>
                                                <td align="center" valign="middle">
                                                    <div>
                                                        <p><b>Здравствуйте!</b>
                                                        </p>
                                                        <p>{!! trans($data['description'], ['course_name' => $data['course_name']])!!}
                                                        </p>
                                                        {{--<button class="btn btn-success"--}}
                                                                {{--style="color: white;"--}}
                                                                {{--name="action" value="confirm">{{__('notifications.confirm_btn_title')}}</button>--}}
                                                        {{--&nbsp;&nbsp;--}}
                                                        {{--<button class="btn btn-danger"--}}
                                                                {{--style="color: white;"--}}
                                                                {{--name="action" value="reject">{{__('notifications.reject_btn_title')}}</button>--}}
                                                    </div>
                                                </td>
                                            </tr>
                                            </form>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top">
                            <p style="color:#7f7f7f;font-size:12px;padding:20px 0">
                                © {{ date('Y') }} {{ env('APP_NAME', '') }}.
                                Все
                                права сохранены.</p>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
