<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Document</title>
    <style>
        @font-face {
            font-family: 'Montserrat';
            src: url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-ExtraLightItalic.eot');
            src: url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-ExtraLightItalic.eot?#iefix') format('embedded-opentype'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-ExtraLightItalic.woff') format('woff'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-ExtraLightItalic.ttf') format('truetype'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-ExtraLightItalic.svg#Montserrat-ExtraLightItalic') format('svg');
            font-weight: 200;
            font-style: italic;
            font-display: swap;
        }

        @font-face {
            font-family: 'Montserrat';
            src: url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-Italic.eot');
            src: url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-Italic.eot?#iefix') format('embedded-opentype'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-Italic.woff') format('woff'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-Italic.ttf') format('truetype'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-Italic.svg#Montserrat-Italic') format('svg');
            font-weight: normal;
            font-style: italic;
            font-display: swap;
        }

        @font-face {
            font-family: 'Montserrat';
            src: url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-Medium.eot');
            src: url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-Medium.eot?#iefix') format('embedded-opentype'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-Medium.woff') format('woff'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-Medium.ttf') format('truetype'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-Medium.svg#Montserrat-Medium') format('svg');
            font-weight: 500;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Montserrat';
            src: url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-Light.eot');
            src: url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-Light.eot?#iefix') format('embedded-opentype'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-Light.woff') format('woff'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-Light.ttf') format('truetype'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-Light.svg#Montserrat-Light') format('svg');
            font-weight: 300;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Montserrat';
            src: url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-LightItalic.eot');
            src: url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-LightItalic.eot?#iefix') format('embedded-opentype'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-LightItalic.woff') format('woff'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-LightItalic.ttf') format('truetype'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-LightItalic.svg#Montserrat-LightItalic') format('svg');
            font-weight: 300;
            font-style: italic;
            font-display: swap;
        }

        @font-face {
            font-family: 'Montserrat';
            src: url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-Bold.eot');
            src: url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-Bold.eot?#iefix') format('embedded-opentype'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-Bold.woff') format('woff'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-Bold.ttf') format('truetype'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-Bold.svg#Montserrat-Bold') format('svg');
            font-weight: bold;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Montserrat';
            src: url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-ThinItalic.eot');
            src: url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-ThinItalic.eot?#iefix') format('embedded-opentype'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-ThinItalic.woff') format('woff'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-ThinItalic.ttf') format('truetype'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-ThinItalic.svg#Montserrat-ThinItalic') format('svg');
            font-weight: 100;
            font-style: italic;
            font-display: swap;
        }

        @font-face {
            font-family: 'Montserrat';
            src: url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-Black.eot');
            src: url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-Black.eot?#iefix') format('embedded-opentype'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-Black.woff') format('woff'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-Black.ttf') format('truetype'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-Black.svg#Montserrat-Black') format('svg');
            font-weight: 900;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Montserrat';
            src: url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-BoldItalic.eot');
            src: url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-BoldItalic.eot?#iefix') format('embedded-opentype'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-BoldItalic.woff') format('woff'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-BoldItalic.ttf') format('truetype'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-BoldItalic.svg#Montserrat-BoldItalic') format('svg');
            font-weight: bold;
            font-style: italic;
            font-display: swap;
        }

        @font-face {
            font-family: 'Montserrat';
            src: url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-MediumItalic.eot');
            src: url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-MediumItalic.eot?#iefix') format('embedded-opentype'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-MediumItalic.woff') format('woff'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-MediumItalic.ttf') format('truetype'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-MediumItalic.svg#Montserrat-MediumItalic') format('svg');
            font-weight: 500;
            font-style: italic;
            font-display: swap;
        }

        @font-face {
            font-family: 'Montserrat';
            src: url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-BlackItalic.eot');
            src: url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-BlackItalic.eot?#iefix') format('embedded-opentype'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-BlackItalic.woff') format('woff'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-BlackItalic.ttf') format('truetype'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-BlackItalic.svg#Montserrat-BlackItalic') format('svg');
            font-weight: 900;
            font-style: italic;
            font-display: swap;
        }

        @font-face {
            font-family: 'Montserrat';
            src: url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-Thin.eot');
            src: url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-Thin.eot?#iefix') format('embedded-opentype'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-Thin.woff') format('woff'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-Thin.ttf') format('truetype'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-Thin.svg#Montserrat-Thin') format('svg');
            font-weight: 100;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Montserrat';
            src: url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-SemiBold.eot');
            src: url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-SemiBold.eot?#iefix') format('embedded-opentype'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-SemiBold.woff') format('woff'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-SemiBold.ttf') format('truetype'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-SemiBold.svg#Montserrat-SemiBold') format('svg');
            font-weight: 600;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Montserrat';
            src: url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-Regular.eot');
            src: url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-Regular.eot?#iefix') format('embedded-opentype'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-Regular.woff') format('woff'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-Regular.ttf') format('truetype'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-Regular.svg#Montserrat-Regular') format('svg');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Montserrat';
            src: url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-SemiBoldItalic.eot');
            src: url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-SemiBoldItalic.eot?#iefix') format('embedded-opentype'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-SemiBoldItalic.woff') format('woff'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-SemiBoldItalic.ttf') format('truetype'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-SemiBoldItalic.svg#Montserrat-SemiBoldItalic') format('svg');
            font-weight: 600;
            font-style: italic;
            font-display: swap;
        }

        @font-face {
            font-family: 'Montserrat';
            src: url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-ExtraBold.eot');
            src: url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-ExtraBold.eot?#iefix') format('embedded-opentype'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-ExtraBold.woff') format('woff'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-ExtraBold.ttf') format('truetype'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-ExtraBold.svg#Montserrat-ExtraBold') format('svg');
            font-weight: 800;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Montserrat';
            src: url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-ExtraLight.eot');
            src: url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-ExtraLight.eot?#iefix') format('embedded-opentype'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-ExtraLight.woff') format('woff'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-ExtraLight.ttf') format('truetype'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-ExtraLight.svg#Montserrat-ExtraLight') format('svg');
            font-weight: 200;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Montserrat';
            src: url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-ExtraBoldItalic.eot');
            src: url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-ExtraBoldItalic.eot?#iefix') format('embedded-opentype'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-ExtraBoldItalic.woff') format('woff'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-ExtraBoldItalic.ttf') format('truetype'),
            url('{{env('APP_URL')}}/assets/fonts/Montserrat/Montserrat-ExtraBoldItalic.svg#Montserrat-ExtraBoldItalic') format('svg');
            font-weight: 800;
            font-style: italic;
            font-display: swap;
        }

        html {
            width: 100%;
            height: 100%;
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat';
            /*font-weight: 500;*/
            margin: 0;
            width: 793px;
            height: 100%;
            position: relative;
            box-sizing: unset;
        }

        * {
            /*box-sizing: border-box;*/
        }

        .diploma {
            text-align: center;
            width: 793px;
            height: 1122px;
            position: absolute;
            top: 0;
            left: 0;
            background-image: url("{{env('APP_URL')}}/assets/img/certificates/3-borders.png");
            background-size: 100% 100%;
            background-repeat: no-repeat;
            color: #302f30;
            background-position: center center;
        }
        .diploma__inner {
            width: 474px;
            height: 768px;
            position: absolute;
            top: 171px;
            left: 159px;
        }
        .diploma__title {
            margin-bottom: 34px;
            text-transform: uppercase;
            font-size: 51px;
            font-weight: 700;
            letter-spacing: 2.5px;
            line-height: 100%;
        }
        .diploma__text {
            font-size: 12px;
            margin-bottom: 9px;
            text-transform: uppercase;
            line-height: 120%;
        }
        .diploma__text strong {
            font-weight: 600;
        }
        .diploma__text strong span {
            font-size: 15px;
        }
        .diploma__name {
            border-bottom: 2px solid;
            text-transform: uppercase;
            font-size: 32px;
            padding-bottom: 15px;
            border-bottom: 1px solid;
            margin: 26px 0 22px;
            line-height: 85%;
            border-color: #B38038;
        }
        .diploma__text-cursive {
            font-size: 12px;
            color: #929292;
            text-transform: uppercase;
            line-height: 120%;
            font-style: italic;
        }
        .diploma__info {
            font-size: 11.51px;
            text-align: left;
            position: absolute;
            left: 0;
            top: 680px;
            width: 140px;
            line-height: 100%;
        }
        .diploma__info strong {
            font-size: 13.43px;
            font-weight: 600;
            margin-top: 3px;
            display: block;
        }
        .diploma__info hr {
            border: none;
            border-bottom: 1px solid #e4e4e4;
            margin: 7.5px 0;
        }
        .diploma__logo {
            width: 137px;
            position: absolute;
            left: 337px;
            top: 722px;
        }
    </style>
</head>
<body>
<!---->
<div class="diploma">
    <div class="diploma__inner">
        <div class="diploma__title">
            Сертификат
        </div>
        <div class="diploma__text">
            Настоящим сертификатом <br/>
            <strong><span>«{{$data['author_name']}}»</span></strong><br>
            подтверждает, что
        </div>
        <div class="diploma__name">
            {{$data['student_name']}}
        </div>
        <div class="diploma__text">
            в течение {{round($data['duration'] / 60)}} часа/ов прошел(ла) курс<br/>
            <strong><span>«{{$data['course_name']}}»</span></strong>,<br/>
            предоставленный через <strong>Enbek.kz</strong>,<br/>
            и получил(а) навык/навыки:
        </div>
        <div class="diploma__text-cursive">
            @foreach($data['skills'] as $skill)
                - {{$skill->name_ru}}<br/>
            @endforeach
        </div>

        <div class="diploma__info">
                            <span>Идентификационный<br/>
                                номер сертификата<br/><strong>{{$data['certificate_id']}}</strong></span>
            <hr>
            <span>Дата выдачи:<br/> {{date('d.m.Y')}}</span>
        </div>
        <img src="{{env('APP_URL')}}/assets/img/certificates/certificate-logo.png" alt=""
             class="diploma__logo">
    </div>
</div>
<!---->
</body>
</html>