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
            font-family: 'Gotham Pro';
            font-weight: bold;
            font-style: italic;
        }

        @font-face {
            font-family: 'Gotham Pro';
            font-weight: normal;
            font-style: italic;
        }

        @font-face {
            font-family: 'Gotham Pro';
            font-weight: 500;
            font-style: normal;
        }

        @font-face {
            font-family: 'Gotham Pro';
            font-weight: 900;
            font-style: italic;
        }

        @font-face {
            font-family: 'Gotham Pro';
            font-weight: bold;
            font-style: normal;
        }

        @font-face {
            font-family: 'Gotham Pro Narrow';
            font-weight: bold;
            font-style: normal;
        }

        @font-face {
            font-family: 'Gotham Pro Narrow';
            font-weight: 500;
            font-style: normal;
        }

        @font-face {
            font-family: 'Gotham Pro';
            font-weight: 300;
            font-style: italic;
        }

        @font-face {
            font-family: 'Gotham Pro';
            font-weight: 300;
            font-style: normal;
        }

        @font-face {
            font-family: 'Gotham Pro';
            font-weight: 900;
            font-style: normal;
        }

        @font-face {
            font-family: 'Gotham Pro';
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'Gotham Pro';
            font-weight: 500;
            font-style: italic;
        }

        html {
            width: 100%;
            height: 100%;
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Gotham Pro';
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
            background-image: url("http://dev14.panama.kz/assets/img/certificates/3-borders.png");
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
        }
        .diploma__text {
            font-size: 12px;
            margin-bottom: 9px;
            text-transform: uppercase;
            line-height: 155%;
        }
        .diploma__text strong {
            font-weight: 500;
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
            line-height: 112%;
            border-color: #B38038;
        }
        .diploma__text-cursive {
            font-size: 12px;
            color: #929292;
            text-transform: uppercase;
            line-height: 141%;
            font-style: italic;
        }
        .diploma__info {
            font-size: 11.51px;
            text-align: left;
            position: absolute;
            left: 0;
            top: 680px;
            width: 140px;
            line-height: 120%;
        }
        .diploma__info strong {
            font-size: 13.43px;
            font-weight: 500;
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
            <strong><span>«{{$data['author_name']}}»</span></strong><br>
            осы сертификатпен
        </div>
        <div class="diploma__name">
            {{$data['student_name']}}
        </div>
        <div class="diploma__text">
            <span>{{round($data['duration'] / 60)}} сағат көлемінде</span><br/>
            <strong><span>«{{$data['course_name']}}»</span></strong>,<br/>
            <strong>Enbek.kz</strong> арқылы берілген,<br/>
            курстан өткенін және келесі қабілеттерге:
        </div>
        <div class="diploma__text-cursive">
            @foreach($data['skills'] as $skill)
                - {{$skill->name_kk}}<br/>
            @endforeach
        </div>
        <div class="diploma__text">
            ие болғанын растайды
        </div>

        <div class="diploma__info">
            <span>Куәліктің сәйкестендіру нөмірі:<br/><strong>00001012020</strong></span>
            <hr>
            <span>Берілген күні:<br/> {{date('d.m.Y')}}</span>
        </div>
        <img src="http://dev14.panama.kz/assets/img/certificates/certificate-logo.png" alt=""
             class="diploma__logo">
    </div>
</div>
<!---->
</body>
</html>