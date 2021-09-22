
<div id="regionModal" class="modal-form">
    <h3 class="title-primary text-center">{{__('default.pages.auth.region_dict')}}</h3>
    <div class="regions">
        <ul class="menu">
            @foreach ($regions as $region)
                @if ($region->isFolder)
                    <li class="dropdown">
                        <a class="plain-text" href="javascript:;" title="{{ $region->caption }}">{{ $region->caption }}</a>
                        <ul style="display: none;">
                            @foreach ($region->childs as $child)
                                <li class="plain-text" data-id="{{ $child->cod }}"><span>{{ $child->caption }}</span></li>
                            @endforeach
                        </ul>
                    </li>
                @else
                    <li data-id="{{ $region->cod }}">{{ $region->caption }}</li>
                @endif
            @endforeach
        </ul>
    </div>
    <div class="text-center">
        <div class="form-group">
            <button type="submit" class="btn btn-primary region-select">{{__('default.pages.auth.select')}}</button>
        </div>
    </div>
</div><!-- /.modal -->
<style>
    #regionModal {
        width: 35em!important;
        padding: 2em 2.5em 1em;
    }
    #regionModal h3 {
        margin-bottom: 0.5em;
    }
    .regions .menu ul {
        position: relative;
        margin-left: 0.5em;
        padding: 0;
    }
    .regions .menu ul li {
        position: relative;
        margin: 0;
        padding: 0 1.25em;
        line-height: 2em;
        position: relative;
        cursor: pointer;
    }
    .regions .menu ul li span {
        padding:3px 5px;
    }
    .regions .menu ul:before {
        content:"";
        display:block;
        width:0;
        position:absolute;
        top:0;
        bottom:0;
        left:0;
        border-left:1px dashed;
    }
    .regions .menu ul li:before {
        content:"";
        display:block;
        width:15px;
        height:0;
        border-top:1px dashed;
        margin-top:-1px;
        position:absolute;
        top:1em;
        left:0
    }
    .regions li {
        list-style:none;
        padding: 0;
        margin: 0!important;
        line-height: 2em;
    }
    .regions ul.menu {
        padding: 0;
    }
    .regions li.selected span {
        color:#fff;
        background-color:#555;
    }
    .glyphicon {
        position: relative;
        top: 1px;
        display: inline-block;
        font-family: 'Glyphicons Halflings';
        font-style: normal;
        font-weight: 400;
        line-height: 1;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
</style>
<script type="text/javascript">
    /*Sidebar nav*/
    $('.regions .dropdown > a').on('click', function () {
        if (!$('.regions').hasClass('mini')) {
            var li = $(this).parent(),
                ul = $(this).siblings('ul');
            if (!li.hasClass('opened')) {
                li.addClass('opened').siblings('.opened').removeClass('opened').find('ul').slideUp('fast');
                ul.slideDown('fast');
            } else {
                ul.slideUp('fast');
                li.removeClass('opened');
            }
        }
    });

    $('body').on('click', '.regions li:not(.dropdown)', function() {
        $('.regions li:not(.dropdown)').removeClass('selected')
        $(this).toggleClass('selected')
    })

    $('body').on('click', '.region-select', function(e) {
        var parent = $('.menu li:not(.dropdown).selected').parents('.dropdown')
        var id = $('.menu li:not(.dropdown).selected').data('id')
        var region_text = $('> a', parent).text()
        var raion_text = $('.menu li:not(.dropdown).selected').text()

        if (id == undefined) {
            return false
        }

        $('input#region_caption').val(region_text + ' / ' + raion_text)
        $('input[type=hidden][name=region_id]').val(id)
        $.fancybox.close({
            src: '#regionModal',
            touch: false
        });
        $('#remove-region').show()

        $('#locality-group').show()
        searchLocality(id)
    })
    $('#remove-region').on('click', function() {
        $('input#region_caption').val(null)
        $('input[type=hidden][name=region_id]').val(null)
        $('#remove-region').hide()
        $('#address').empty()
        var selectize = $("#address")[0].selectize;
        selectize.enable();
        selectize.clear();
        selectize.clearOptions();
        $('#locality-group').hide()
        $('.menu li:not(.dropdown).selected').removeClass('selected')
    })
</script>
