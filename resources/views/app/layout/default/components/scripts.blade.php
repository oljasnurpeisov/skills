<script src="/assets/libs/jquery/dist/jquery.js"></script>
<script src="https://cdn.tiny.cloud/1/c0cnq2qz6yd8ycmigfdsxz6lq0yrz0njkstn32tcnwiij71c/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script src="/assets/libs/maskedinput/maskedinput.js"></script>
<script src="/assets/libs/fancybox/dist/jquery.fancybox.min.js"></script>
<script src="/assets/libs/slick-carousel/slick/slick.js"></script>
<script src="/assets/libs/dropzone/dropzone.min.js"></script>
<script src="/assets/libs/dropzone/locale.js"></script>
<script src="/assets/libs/nouislider/distribute/nouislider.js"></script>
<script src="/assets/libs/selectize/selectize.js"></script>
<script src="/assets/libs/air-datepicker/dist/js/datepicker.js"></script>
<script src="/assets/libs/visually-impaired/js/js.cookie.min.js"></script>
<script src="/assets/libs/visually-impaired/js/bvi.min.js"></script>
<script src="/assets/libs/momentjs/moment-with-locales.js"></script>
<script src="/assets/js/ajax-select.js"></script>
<script src="/assets/js/service.js"></script>
<script src="/assets/js/scripts.js"></script>


@guest
    <script type="text/javascript">
        @if ($errors->has('email'))
        $.fancybox.open({
            src: '#authorAuth',
            touch: false
        });
        @endif
        @if ($errors->has('email_forgot_password'))
        $.fancybox.open({
            src: '#passwordRecovery',
            touch: false
        });
        @endif
        @if (session('recovery_pass'))
        $.fancybox.open({
            src: '#authorAuth',
            touch: false
        });
        @endif
        @if ($errors->has('email_register') or $errors->has('password_register') or $errors->has('password_register_confirmation')
        or $errors->has('iin') or $errors->has('company_name') or $errors->has('company_logo'))
        $.fancybox.open({
            src: '#authorRegistration',
            touch: false
        });
        @endif
        @if (session('failed'))
        $.fancybox.open({
            src: '#studentAuth',
            touch: false
        });
        @endif
        @if(Session::get('resume_data') or $errors->has('resume_iin') or $errors->has('resume_name'))
        $.fancybox.open({
            src: '#noCvModal',
            touch: false
        });
        {{Session::forget('resume_data')}}
        @endif
        @if(Session::get('agree_data'))
        $.fancybox.open({
            src: '#agreeModal',
            touch: false
        });
        {{Session::forget('agree_data')}}
        @endif
    </script>
@endguest
