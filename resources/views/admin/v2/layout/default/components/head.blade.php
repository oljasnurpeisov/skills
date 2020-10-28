<meta charset="UTF-8">
<title>@yield('title',__('default.site_name'))</title>

<link rel="apple-touch-icon" sizes="180x180" href="/assets/admin/favicon/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/assets/admin/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/assets/admin/favicon/favicon-16x16.png">
<link rel="manifest" href="/assets/admin/favicon/site.webmanifest">
<link rel="mask-icon" href="/assets/admin/favicon/safari-pinned-tab.svg" color="#5bbad5">
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
<script>window.Laravel = <?php echo json_encode(['csrfToken' => csrf_token()]); ?></script>

<meta name="viewport" content="width=device-width">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" type="text/css">
<link rel="stylesheet" href="/assets/admin/libs/fancybox/dist/jquery.fancybox.min.css" type="text/css" media="screen"/>
<link rel="stylesheet" href="/assets/admin/libs/chosen/chosen.css" type="text/css" media="screen"/>
<link rel="stylesheet" href="/assets/admin/libs/air-datepicker/dist/css/datepicker.css" type="text/css" media="screen"/>
<link rel="stylesheet" href="/assets/admin/css/style.css" type="text/css" media="screen"/>

<script src="/assets/admin/libs/jquery/dist/jquery.js"></script>

<style>
    .hidden{
        display: none;
    }

    .file-upload-image{
        height: 100%;
        object-fit: contain;
    }
</style>
