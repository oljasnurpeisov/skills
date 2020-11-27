<script src="/assets/libs/jquery/dist/jquery.js"></script>
<script src="https://cdn.tiny.cloud/1/dypqwpg7c59aateufscg61hqfrr4d8ylm54905fckvunt7pg/tinymce/5/tinymce.min.js"
        referrerpolicy="origin"></script>
<script src="/assets/libs/maskedinput/maskedinput.js"></script>
<script src="/assets/libs/fancybox/dist/jquery.fancybox.min.js"></script>
<script src="/assets/libs/slick-carousel/slick/slick.js"></script>
<!--<script src="/assets/libs/chosen/chosen.jquery.js"></script>-->
{{--<script src="/assets/libs/simplebar/simplebar.js"></script>--}}
<script src="/assets/libs/air-datepicker/dist/js/datepicker.js"></script>
<script src="/assets/libs/dropzone/dropzone.min.js"></script>
<script src="/assets/libs/dropzone/locale.js"></script>
<script src="/assets/libs/nouislider/distribute/nouislider.js"></script>
<script src="/assets/libs/selectize/selectize.js"></script>
<script src="/assets/libs/air-datepicker/dist/js/datepicker.js"></script>
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
    </script>
@endguest
