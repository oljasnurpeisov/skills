<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv=" Content-Type " content="text/html; charset=utf-8 "/>
    <title>Document</title>
    <style>
        @font-face {
            font-family: 'Montserrat';
            src: url('{{ public_path('/assets/fonts/Montserrat/Montserrat-ExtraLightItalic.eot') }}');
            src: url('{{ public_path('/assets/fonts/Montserrat/Montserrat-ExtraLightItalic.eot?#iefix') }}') format('embedded-opentype'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-ExtraLightItalic.woff')}}') format('woff'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-ExtraLightItalic.ttf')}}') format('truetype'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-ExtraLightItalic.svg#Montserrat-ExtraLightItalic')}}') format('svg');
            font-weight: 200;
            font-style: italic;
            font-display: swap;
        }

        @font-face {
            font-family: 'Montserrat';
            src: url('{{ public_path('/assets/fonts/Montserrat/Montserrat-Italic.eot') }}');
            src: url('{{ public_path('/assets/fonts/Montserrat/Montserrat-Italic.eot?#iefix')}}') format('embedded-opentype'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-Italic.woff')}}') format('woff'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-Italic.ttf')}}') format('truetype'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-Italic.svg#Montserrat-Italic')}}') format('svg');
            font-weight: normal;
            font-style: italic;
            font-display: swap;
        }

        @font-face {
            font-family: 'Montserrat';
            src: url('{{ public_path('/assets/fonts/Montserrat/Montserrat-Medium.eot') }}');
            src: url('{{ public_path('/assets/fonts/Montserrat/Montserrat-Medium.eot?#iefix')}} ') format('embedded-opentype'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-Medium.woff')}}') format('woff'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-Medium.ttf')}}') format('truetype'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-Medium.svg#Montserrat-Medium')}}') format('svg');
            font-weight: 500;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Montserrat';
            src: url('{{ public_path('/assets/fonts/Montserrat/Montserrat-Light.eot')}} ');
            src: url('{{ public_path('/assets/fonts/Montserrat/Montserrat-Light.eot?#iefix')}} ') format('embedded-opentype'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-Light.woff')}}') format('woff'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-Light.ttf')}}') format('truetype'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-Light.svg#Montserrat-Light')}}') format('svg');
            font-weight: 300;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Montserrat';
            src: url('{{ public_path('/assets/fonts/Montserrat/Montserrat-LightItalic.eot')}} ');
            src: url('{{ public_path('/assets/fonts/Montserrat/Montserrat-LightItalic.eot?#iefix') }} ') format('embedded-opentype'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-LightItalic.woff')}}') format('woff'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-LightItalic.ttf')}}') format('truetype'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-LightItalic.svg#Montserrat-LightItalic')}}') format('svg');
            font-weight: 300;
            font-style: italic;
            font-display: swap;
        }

        @font-face {
            font-family: 'Montserrat';
            src: url('{{ public_path('/assets/fonts/Montserrat/Montserrat-Bold.eot') }}');
            src: url('{{ public_path('/assets/fonts/Montserrat/Montserrat-Bold.eot?#iefix') }}') format('embedded-opentype'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-Bold.woff')}}') format('woff'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-Bold.ttf')}}') format('truetype'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-Bold.svg#Montserrat-Bold')}}') format('svg');
            font-weight: bold;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Montserrat';
            src: url('{{ public_path('/assets/fonts/Montserrat/Montserrat-ThinItalic.eot') }}');
            src: url('{{ public_path('/assets/fonts/Montserrat/Montserrat-ThinItalic.eot?#iefix') }}') format('embedded-opentype'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-ThinItalic.woff')}}') format('woff'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-ThinItalic.ttf')}}') format('truetype'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-ThinItalic.svg#Montserrat-ThinItalic')}}') format('svg');
            font-weight: 100;
            font-style: italic;
            font-display: swap;
        }

        @font-face {
            font-family: 'Montserrat';
            src: url('{{ public_path('/assets/fonts/Montserrat/Montserrat-Black.eot') }}');
            src: url('{{ public_path('/assets/fonts/Montserrat/Montserrat-Black.eot?#iefix') }}') format('embedded-opentype'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-Black.woff')}}') format('woff'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-Black.ttf')}}') format('truetype'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-Black.svg#Montserrat-Black')}}') format('svg');
            font-weight: 900;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Montserrat';
            src: url('{{ public_path('/assets/fonts/Montserrat/Montserrat-BoldItalic.eot') }}');
            src: url('{{ public_path('/assets/fonts/Montserrat/Montserrat-BoldItalic.eot?#iefix') }}') format('embedded-opentype'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-BoldItalic.woff')}}') format('woff'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-BoldItalic.ttf')}}') format('truetype'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-BoldItalic.svg#Montserrat-BoldItalic')}}') format('svg');
            font-weight: bold;
            font-style: italic;
            font-display: swap;
        }

        @font-face {
            font-family: 'Montserrat';
            src: url('{{ public_path('/assets/fonts/Montserrat/Montserrat-MediumItalic.eot') }}');
            src: url('{{ public_path('/assets/fonts/Montserrat/Montserrat-MediumItalic.eot?#iefix')}} ') format('embedded-opentype'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-MediumItalic.woff')}}') format('woff'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-MediumItalic.ttf')}}') format('truetype'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-MediumItalic.svg#Montserrat-MediumItalic')}}') format('svg');
            font-weight: 500;
            font-style: italic;
            font-display: swap;
        }

        @font-face {
            font-family: 'Montserrat';
            src: url('{{public_path('/assets/fonts/Montserrat/Montserrat-BlackItalic.eot')}}');
            src: url('{{public_path('/assets/fonts/Montserrat/Montserrat-BlackItalic.eot?#iefix')}}') format('embedded-opentype'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-BlackItalic.woff')}}') format('woff'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-BlackItalic.ttf')}}') format('truetype'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-BlackItalic.svg#Montserrat-BlackItalic')}}') format('svg');
            font-weight: 900;
            font-style: italic;
            font-display: swap;
        }

        @font-face {
            font-family: 'Montserrat';
            src: url('{{ public_path('/assets/fonts/Montserrat/Montserrat-Thin.eot') }}');
            src: url('{{ public_path('/assets/fonts/Montserrat/Montserrat-Thin.eot?#iefix') }}') format('embedded-opentype'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-Thin.woff')}}') format('woff'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-Thin.ttf')}}') format('truetype'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-Thin.svg#Montserrat-Thin')}}') format('svg');
            font-weight: 100;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Montserrat';
            src: url('{{ public_path('/assets/fonts/Montserrat/Montserrat-SemiBold.eot') }}');
            src: url('{{ public_path('/assets/fonts/Montserrat/Montserrat-SemiBold.eot?#iefix') }}') format('embedded-opentype'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-SemiBold.woff')}}') format('woff'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-SemiBold.ttf')}}') format('truetype'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-SemiBold.svg#Montserrat-SemiBold')}}') format('svg');
            font-weight: 600;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Montserrat';
            src: url('{{public_path('/assets/fonts/Montserrat/Montserrat-Regular.eot')}}');
            src: url('{{public_path('/assets/fonts/Montserrat/Montserrat-Regular.eot?#iefix')}}') format('embedded-opentype'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-Regular.woff')}}') format('woff'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-Regular.ttf')}}') format('truetype'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-Regular.svg#Montserrat-Regular')}}') format('svg');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Montserrat';
            src: url('{{public_path('/assets/fonts/Montserrat/Montserrat-SemiBoldItalic.eot')}}');
            src: url('{{public_path('/assets/fonts/Montserrat/Montserrat-SemiBoldItalic.eot?#iefix')}}') format('embedded-opentype'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-SemiBoldItalic.woff')}}') format('woff'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-SemiBoldItalic.ttf')}}') format('truetype'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-SemiBoldItalic.svg#Montserrat-SemiBoldItalic')}}') format('svg');
            font-weight: 600;
            font-style: italic;
            font-display: swap;
        }

        @font-face {
            font-family: 'Montserrat';
            src: url('{{public_path('/assets/fonts/Montserrat/Montserrat-ExtraBold.eot')}}');
            src: url('{{public_path('/assets/fonts/Montserrat/Montserrat-ExtraBold.eot?#iefix')}}') format('embedded-opentype'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-ExtraBold.woff')}}') format('woff'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-ExtraBold.ttf')}}') format('truetype'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-ExtraBold.svg#Montserrat-ExtraBold')}}') format('svg');
            font-weight: 800;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Montserrat';
            src: url('{{public_path('/assets/fonts/Montserrat/Montserrat-ExtraLight.eot')}}');
            src: url('{{public_path('/assets/fonts/Montserrat/Montserrat-ExtraLight.eot?#iefix')}}') format('embedded-opentype'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-ExtraLight.woff')}}') format('woff'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-ExtraLight.ttf')}}') format('truetype'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-ExtraLight.svg#Montserrat-ExtraLight')}}') format('svg');
            font-weight: 200;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Montserrat';
            src: url('{{public_path('/assets/fonts/Montserrat/Montserrat-ExtraBoldItalic.eot')}}');
            src: url('{{public_path('/assets/fonts/Montserrat/Montserrat-ExtraBoldItalic.eot?#iefix')}}') format('embedded-opentype'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-ExtraBoldItalic.woff')}}') format('woff'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-ExtraBoldItalic.ttf')}}') format('truetype'), url('{{public_path('/assets/fonts/Montserrat/Montserrat-ExtraBoldItalic.svg#Montserrat-ExtraBoldItalic')}}') format('svg');
            font-weight: 800;
            font-style: italic;
            font-display: swap;
        }

        html {
            width: 100%;
            height: 100%;
            padding: 0;
            margin: 0;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat';
            margin: 0;
            width: 1125px;
            height: 910px;
            background-color: #f5f5f5;
            background-image: url("{{ public_path('/assets/img/certificates/cert-bg.png') }}");
            background-size: cover;
            line-height: 100%;
            position: relative;
            font-size: 13px;
        }

        * {
            box-sizing: border-box;
        }

        .border-image {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            width: 1125px;
            height: 795px;
        }

        .diploma {
            width: 100%;
            color: #2c2e35;
            background-repeat: no-repeat;
            max-height: 100%;
            max-width: 100%;
            padding: 60px 50px;
            background-position: top left;
        }

        .diploma__title {
            margin-bottom: 20px;
            text-transform: uppercase;
            font-size: 16px;
            font-weight: 800;
            line-height: 16px;
            text-align: center;
        }

        .diploma__title-2 {
            font-size: 50px;
            font-weight: 800;
            text-align: center;
            line-height: 40px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .diploma__aftertitle {
            margin-bottom: 20px;
            text-align: center;
        }

        .diploma__fio {
            text-align: center;
            font-size: 30px;
            font-weight: 700;
            margin-bottom: 25px;
        }

        .diploma__text_column {
            font-size: 13px;
        }

        .diploma__text-container {
            margin-bottom: 30px;
        }

        .diploma__text_quotes {
            font-size: 18px;
            width: 15px;
        }

        .diploma__text_field {
            height: 18px;
            background: #fff;
            border: 1px solid #efeff0;
            -webkit-border-radius: 20px;
            border-radius: 20px;
            width: 90px;
            padding: 0 4px;
            overflow: hidden;
        }

        .diploma__footer .diploma__text_column {
            font-size: 11px;
        }

        .diploma__footer-title {
            font-size: 18px;
            padding-left: 16px;
            font-weight: 800;
        }

        .diploma__text_middle-field {
            width: 53px;
            -webkit-border-radius: 10px;
            border-radius: 10px;
        }

        .diploma__text_short-field {
            width: 30px;
        }

        .diploma__text_long-field {
            background: #fff;
            border: 1px solid #efeff0;
            box-sizing: border-box;
            border-radius: 10px;
            width: 480px;
        }

        .manyline-field {
            padding: 0 8px;
            overflow: hidden;
            line-height: 19px;
            position: absolute;
        }

        .manyline-field-1 {
            height: 78px;
            width: 474px;
            background-image: url({{public_path('/assets/img/certificates/fieldBg1.png')}});
            background-size: 100%;
            background-repeat: no-repeat;
        }

        .manyline-field-2 {
            height: 140px;
            width: 474px;
            background-image: url({{public_path('/assets/img/certificates/fieldBg2.png')}});
            background-size: 100% 100%;
            background-repeat: no-repeat;
        }

        table {
            width: 100%;
        }
    </style>
</head>

<body>
<!---->
<img src="{{public_path('/assets/img/certificates/cert-border.png')}}" class="border-image">
<div class="diploma ">
    <div class="diploma__inner2 ">
        <div class="diploma__inner ">
            <div class="diploma__title ">
                Қазақстан республикасы еңбек және халықты әлеуметтік қорғау министрлігі<br> министерство труда и
                социальной защиты населения республики Казахстан
            </div>
            <div class="diploma__title-2 ">
                Сертификат
            </div>
            <div class="diploma__aftertitle ">
                подтверждает, что
            </div>
            <div class="diploma__fio ">
                {{$data['student_name']}}
            </div>
            <div class="diploma__text-container ">
                <table>
                    <td width="50%" style="padding-top: 18px">
                        <div class="diploma__text_column " style="width: 100%; margin-right:10px; position: relative;">
                            <div class="diploma__text_quotes" style="left:0;top:3px;position: absolute;">«</div>
                            <div style="left:12px;top:0; " class="manyline-field manyline-field-1">
                                <div style="position: relative; margin-top: -4px;">
                                    <p style="margin: 0; line-height: 19.5px">{{$data['course_name']}}</p>
                                </div>
                            </div>
                            <div class="diploma__text_quotes" style="left:505px;top:54px; position: absolute;">»</div>
                            <div style="left:12px;top:78px; position: absolute;">тақырыбында</div>
                            <div class="diploma__text_field diploma__text_middle-field "
                                 style="left:110px;top:77px; position: absolute;text-align: center;padding: 0;">{{round($data['duration'] / 60)}}</div>
                            <div style="left:168px;top:78px; position: absolute; white-space: nowrap">сағат көлемінде,
                                «Enbek.kz»
                                Электрондық еңбек
                            </div>
                            <div style="left:12px;top:98px; position: absolute;">биржасы арқылы берілген курстан өткенін
                                және келесі дағдыларға:
                            </div>
                            <div style="left:12px;top:122px;" class="manyline-field manyline-field-2">
                                @php($skills = $data['skills']->implode('name_kk', '; '))
                                <div style="position: relative; margin-top: -4px;">
                                    <p style="margin: 0; line-height: 20.5px">{{$skills}};</p>
                                </div>
                            </div>
                            <div style="left:12px;top:265px; position: absolute;">ие болғанын растайды</div>
                        </div>
                    </td>
                    <td width="50%">
                        <div class="diploma__text_column " style="width: 100%; position: relative;">
                            <div style="left:12px;top:0px; position: absolute;">прошел (-ла) курс на тему:</div>
                            <div class="diploma__text_quotes" style="left:0;top:21px;position: absolute;">«</div>
                            <div style="left:12px;top:18px;" class="manyline-field manyline-field-1">
                                <div style="position: relative;">
                                    <div style="position: relative; margin-top: -4px;">
                                        <p style="margin: 0; line-height: 19.5px">{{$data['course_name']}}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="diploma__text_quotes" style="right:-14px;top:72px; position: absolute;">»</div>
                            <div style="left:12px;top:94px; position: absolute;">предоставленный через электронную биржу
                                труда «Enbek.kz» в объеме
                            </div>
                            <div class="diploma__text_field diploma__text_middle-field "
                                 style="left:12px;top:115px; position: absolute;text-align: center;padding: 0;">{{round($data['duration'] / 60)}}</div>
                            <div style="left:70px;top:115px; position: absolute;">часов и получил(ла) навык/навыки:
                            </div>
                            <div style="left:12px;top:140px;" class="manyline-field manyline-field-2">
                                @php($skills = $data['skills']->implode('name_ru', '; '))
                                <div style="position: relative; margin-top: -4px;">
                                    <p style="margin: 0; line-height: 20.5px">{{$skills}};</p>
                                </div>
                            </div>
                        </div>
                    </td>
                </table>
            </div>
            <div class="diploma__footer " style="margin-top: 290px; ">
                <table>
                    <td width="50% ">
                        <div class="diploma__footer-title " style="padding-bottom: 5px; ">Курс авторы / Автор курса:
                        </div>
                        <div class="diploma__text_column " style="width: 100%; position: relative; left:12px; ">
                            <div class="diploma__text_field diploma__text_long-field "
                                 style="top:0; left:0; position: absolute; ">{{ $data['author']->type_ownership->name_short_ru }} "{{$data['author']->company_name}}"</div>
                            <div style="top:24px; left:0; position: absolute; ">Ұйымның атауы/Наименование организации
                            </div>
                            <div class="diploma__text_field diploma__text_long-field "
                                 style="top:45px; left:0; position: absolute; ">{{ $data['author']->author_info->name . ' ' . $data['author']->author_info->surname }}</div>
                            <div style="top:69px; left:0; position: absolute; ">Автордың Т.А.Ә/Ф.И.О автора
                            </div>
                            <div style="top:100px; left:0; position: absolute; ">Сәйкестендіру номері/Идентификационный
                                номер
                            </div>
                            <div class="diploma__text_field "
                                 style="left:300px;top:101px; position: absolute; width: 200px;border-radius: 10px;text-align: center;padding: 0; ">{{$data['certificate_id']}}</div>
                            <div style="top:132px; left:0; position: absolute; ">Берілген күні/Дата выдачи</div>
                            <div style="left:160px;top:130px; position: absolute; ">«</div>
                            <div class="diploma__text_field "
                                 style="left:167px;top:131px; position: absolute; height: 20px; width: 20px;border-radius: 55%;text-align: center;padding: 0; line-height: 12px;">{{date('d')}}</div>
                            <div style="left:191px;top:130px; position: absolute; ">»</div>
                            @php(\Carbon\Carbon::setlocale('ru'))
                            @php($mounth_ru = \Carbon\Carbon::now()->translatedFormat('F'))
                            @php(\Carbon\Carbon::setlocale('kk'))
                            @php($mounth_kk = \Carbon\Carbon::now()->translatedFormat('F'))
                            <div class="diploma__text_field "
                                 style="left:205px;top:131px; position: absolute; width: 120px;border-radius: 10px;text-align: center;padding: 0; line-height: 12px;">{{$mounth_ru}}/{{$mounth_kk}}</div>
                            <div style="left:332px;top:132px; position: absolute; ">{{date('Y')}}ж. (г.)</div>
                    </td>
                    <td width="50% ">
                        <div style="position: relative; ">
                            <img src="{{public_path('/assets/img/certificates/cert-logo.png')}}"
                                 style="width: 250px; right: 20px;position: absolute; top: 40px; ">
                        </div>
                    </td>
                </table>
            </div>
        </div>
    </div>
</div>
<!---->
</body>

</html>
