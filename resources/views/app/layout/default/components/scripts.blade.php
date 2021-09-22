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
{{--        @if(Session::get('resume_data') or $errors->has('resume_iin') or $errors->has('resume_name'))--}}
        $.fancybox.open({
            src: '#noCvModal',
            touch: false
        });
        {{Session::forget('resume_data')}}
        $('#show-region-modal').on('click', function() {
            var btn = $(this)
            btn.prop('disabled', true).text(btn.data('loading'))

            if ($('#regionModal').length == 0) {
                $.ajax({
                    method: 'GET',
                    url: '/{{ $lang }}/getRegions',
                    success: function(html) {
                        $('body').append(html)
                        $.fancybox.open({
                            src: '#regionModal',
                            touch: false
                        });
                        btn.prop('disabled', false).text(btn.data('title'))
                    }
                })
            }
            else {
                $.fancybox.open({
                    src: '#regionModal',
                    touch: false
                });
                btn.prop('disabled', false).text(btn.data('title'))
            }
        });
        function searchLocality(id) {
            $.ajax({
                method: 'GET',
                url: '/{{ $lang }}/getKato/' + id,
                success: function(result){
                    console.log(result);
                    for (var i = result.data.length-1; i >= 0; i--) {
                        $('#address').append('<option value="'+result['data'][i]['id']+'">'+result['data'][i]['name']+'</option>');
                    }
                    var selectize = $("#address")[0].selectize;
                    selectize.enable();
                    selectize.clear();
                    selectize.clearOptions();
                    selectize.load(function(callback) {
                        callback(result.data);
                    });
                    if (result['data'].length == 1) {
                        selectize.setValue(result['data'][0].id);
                    }
                }
            })
        }
        $('#address').selectize({
            valueField: 'id',
            labelField: 'name',
            searchField: 'name',
            options: [],
            create: false,
            render: {
                option: function(data, escape) {
                    return '<div class="option">' + escape(data.name) +'</div>';
                }
            }
        });
{{--        @endif--}}
        @if(Session::get('agree_data'))
        $.fancybox.open({
            src: '#agreeModal',
            touch: false
        });
        {{Session::forget('agree_data')}}
        @endif
    </script>
@endguest
