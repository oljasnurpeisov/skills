<div class="block collapsed">
    <div class="block__header">
        <h2 class="title-secondary">{{ __('default.pages.stat.title') }}</h2>
        <i class="icon-chevron-up btn-collapse"></i>
    </div>

    <div class="block__body" style="display:none;">
        <table class="table report">
            @yield('stat')
        </table>
    </div>
</div>
