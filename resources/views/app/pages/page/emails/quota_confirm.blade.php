<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
      xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <!--[if gte mso 9]>
    <xml>
    <o:OfficeDocumentSettings>
        <o:AllowPNG/>
        <o:PixelsPerInch>96</o:PixelsPerInch>
    </o:OfficeDocumentSettings>
    </xml>
    <![endif]-->
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="date=no">
    <meta name="format-detection" content="address=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="x-apple-disable-message-reformatting">
    <!--[if !mso]><!-->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,700;1,400;1,700&amp;display=swap"
          rel="stylesheet">
    <!--<![endif]-->
    <title></title>
    <!--[if gte mso 9]>
    <style type="text/css" media="all">
    sup {
        font-size: 100% !important;
    }
    </style>
    <![endif]-->
</head>
<body class="body"
      style="padding:0 !important; margin:0 !important; display:block !important; min-width:100% !important; width:100% !important; background:#EEEEEE; -webkit-text-size-adjust:none;">
<script id="__bs_script__">//<![CDATA[
    document.write("<script async src='/browser-sync/browser-sync-client.js?v=2.26.13'><\/script>".replace("HOST", location.hostname));
    //]]></script>
<script async="" src="/browser-sync/browser-sync-client.js?v=2.26.13"></script>


<style type="text/css" media="screen">
    /* Linked Styles */
    body {
        padding: 0 !important;
        margin: 0 !important;
        display: block !important;
        min-width: 100% !important;
        width: 100% !important;
        -webkit-text-size-adjust: none;
        font-family: 'Open Sans', sans-serif;
    }

    a {
        color: #2AB5F6;
        text-decoration: none;
    }

    p {
        padding: 0 !important;
        margin: 0 !important;
    }

    img {
        -ms-interpolation-mode: bicubic; /* Allow smoother rendering of resized image in Internet Explorer */
    }
</style>
<table style="background: #eeeeee; width: 100%;">
    <tr>
        <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f4f4f4" style="
    width: 600px;
    max-width: 100%;
    margin: 42px auto;
    padding: 50px;
    text-align: center;
    font-size: 17px;
    background-color: #fff;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
">
                <tbody>
                <tr>
                    <td style="padding-bottom: 30px;"><a href="{{env('APP_URL')}}" title=""><img
                                    src="{{env('APP_URL')}}/assets/img/logo.png" alt=""></a></td>
                </tr>
                <tr>
                    <td>
                        <p><b>{{__('auth.pages.greeting')}}</b></p>
                        <br>
                        <p style="
    margin: 0;
">{!! trans($data['description'], ['course_name' => $data['course_name']])!!}
                        </p>
                        <br>
                    </td>
                </tr>
                </tbody>
            </table>

            <p style="
    text-align: center;
    font-size: 14px;
    text-transform: uppercase;
    color: #BDBDBD;
">© 2020 <a href="#" style="color: #BDBDBD; text-decoration: none; pointer-events: none">{{env('APP_NAME')}}</a></p>
        </td>
    </tr>
</table>

</body>
</html>