<meta charset="UTF-8">
<title>@yield('title',__('default.site_name'))</title>

<link rel="apple-touch-icon" sizes="180x180" href="/assets/favicon/favicon.jpg">
<link rel="icon" type="image/png" sizes="32x32" href="/assets/favicon/favicon.jpg">
<link rel="icon" type="image/png" sizes="16x16" href="/assets/favicon/favicon.jpg">
<link rel="manifest" href="/assets/favicon/site.webmanifest">
<link rel="mask-icon" href="/assets/favicon/favicon.jpg" color="#5bbad5">
<meta name="msapplication-TileColor" content="#da532c">
<meta name="theme-color" content="#ffffff">

<meta name="title" content="@yield('meta_title', '')">
<meta name="description" content="@yield('meta_description', '')">
<meta name="keywords" content="@yield('meta_keywords', '')">
<meta name="robots" content="@yield('robots', '')">

<meta name="relap-image" content="@yield('meta_img', env('APP_URL').'/assets/admin/img/no-photo.png')">
<meta name="relap-title" content="@yield('meta_title', '')">
<meta name="relap-description" content="@yield('meta_description', '')">

<meta property="og:type" content="@yield('meta_type', 'article')"/>
<meta property="og:title" content="@yield('meta_title', '')"/>
<meta property="og:url" content="{{ \Illuminate\Support\Facades\URL::current() }}"/>
<meta property="og:locale" content="{{ app()->getLocale() === 'ru' ? 'ru_RU' : 'kk_KZ' }}">
<meta property="og:description" content="@yield('meta_description', '')"/>

<meta property="og:image" content="@yield('meta_img', env('APP_URL').'/assets/admin/img/no-photo.png')"/>
<meta property="og:image:url" content="@yield('meta_img', env('APP_URL').'/assets/admin/img/no-photo.png')"/>
<meta property="og:video" content="@yield('meta_video')"/>
<meta property="og:video:type" content="@yield('meta_video_type')"/>

<meta property="vk:image" content="@yield('meta_img', env('APP_URL').'/assets/admin/img/no-photo.png')"/>
<meta property="twitter:image" content="@yield('meta_img', env('APP_URL').'/assets/admin/img/no-photo.png')"/>
<meta property="facebook:image" content="@yield('meta_img', env('APP_URL').'/assets/admin/img/no-photo.png')"/>
<link rel="image_src" href="@yield('meta_img', env('APP_URL').'/assets/admin/img/no-photo.png')"/>

<link rel="canonical" href="{{ \Illuminate\Support\Facades\URL::current() }}">
<link rel="shortlink" href="{{ \Illuminate\Support\Facades\URL::current() }}">

<meta name="csrf-token" content="{{ csrf_token() }}">

<meta name="viewport" content="width=device-width">

<link rel="stylesheet" href="/assets/libs/fancybox/dist/jquery.fancybox.min.css" type="text/css" media="screen"/>
<link rel="stylesheet" href="/assets/libs/slick-carousel/slick/slick.css" type="text/css" media="screen"/>
<link rel="stylesheet" href="/assets/libs/dropzone/dropzone.min.css" type="text/css" media="screen"/>
<link rel="stylesheet" href="/assets/libs/nouislider/distribute/nouislider.min.css" type="text/css" media="screen"/>
<link rel="stylesheet" href="/assets/libs/selectize/selectize.default.css" type="text/css" media="screen"/>
<link rel="stylesheet" href="/assets/libs/air-datepicker/dist/css/datepicker.css" type="text/css" media="screen"/>
<link rel="stylesheet" href="/assets/libs/visually-impaired/css/bvi.min.css" type="text/css" media="screen"/>
<link rel="stylesheet" href="/assets/css/style.css" type="text/css" media="screen"/>

<script src="/assets/admin/libs/jquery/dist/jquery.js"></script>
<script src="/assets/js/kalkan.js"></script>

<script>
    window.Laravel = {
        "csrfToken": "{{ csrf_token() }}",
        "lang": "{{$lang}}"
    }
</script>

<style>
    .plain-text table {
        border-collapse: collapse;
    }

    .plain-text table td, .plain-text table th {
        border: 1px solid #909090;
    }

    .test .item {
        counter-increment: number;
    }

    .test .question > *:first-child:before {
        content: counter(number) ". ";
    }
</style>

<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
    (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
        m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
    (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

    ym(73415044, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true
    });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/73415044" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
