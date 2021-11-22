<footer class="footer">
    <div class="container">
        <div class="row row--multiline">
            <div class="col-md-5">
                <h4 class="footer__title">{{__('default.pages.footer.main_links')}}</h4>
                <ul class="site-map">
                    <li><a href="/{{$lang}}" title="{{__('default.main_title')}}">{{__('default.main_title')}}</a></li>
                    <li><a href="https://www.enbek.kz/" title="{{__('default.pages.footer.enbek_kz')}}"
                           target="_blank">{{__('default.pages.footer.enbek_kz')}}</a></li>
                    <li><a href="/{{$lang}}/for-authors"
                           title="{{__('default.pages.footer.teacher_title')}}">{{__('default.pages.footer.teacher_title')}}</a>
                    </li>
                    <li><a href="/{{$lang}}/help" title="{{__('default.pages.footer.help')}}">{{__('default.pages.footer.help')}}</a>
                    </li>
                    <li><a href="https://www.enbek.kz/docs/ru"
                           title="{{__('default.pages.footer.about_us')}}">{{__('default.pages.footer.about_us')}}</a>
                    </li>
                    <li><a href="/{{$lang}}/faq" title="{{__('default.pages.footer.faq')}}">{{__('default.pages.footer.faq')}}</a>
                    </li>
                    @auth
                        @php
                            $tech_support = App\Models\User::whereHas('roles', function ($q) {
                $q->where('slug', '=', 'tech_support');
            })->first();
                        @endphp
                        <li><a href="/{{$lang}}/dialog/opponent-{{$tech_support->id}}"
                               title="{{__('default.pages.footer.feedback')}}">{{__('default.pages.footer.feedback')}}</a>
                        </li>
                    @else
                        <li><a href="#authorization" data-fancybox
                               title="{{__('default.pages.footer.feedback')}}">{{__('default.pages.footer.feedback')}}</a>
                        </li>
                    @endif
                    <li><a href="#errorOnPage" data-fancybox
                           title="{{__('default.error_on_page')}}">{{__('default.error_on_page')}}</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-4">
                <h4 class="footer__title">{{__('default.pages.footer.contacts')}}</h4>
                <div class="footer__contacts">
                    <p><span>{{__('default.pages.footer.address_title')}}:</span> {{__('default.pages.footer.address')}}
                    </p>
                    <p><span>{{__('default.pages.footer.email_title')}}:</span> <a
                                href="mailto:{{__('default.pages.footer.email')}}"
                                title="{{__('default.pages.footer.email')}}">{{__('default.pages.footer.email')}}</a>
                    </p>
                    <p><span>{{__('default.pages.footer.phone_title')}}:</span> <a
                                href="tel:{{__('default.pages.footer.phone')}}"
                                title="{{__('default.pages.footer.phone')}}">{{__('default.pages.footer.phone')}}</a>
                    </p>
                </div>
            </div>
            <div class="col-md-3">
                <h4 class="footer__title">{{__('default.pages.footer.social_networks')}}</h4>
                <ul class="socials">
                    <li><a href="https://www.instagram.com/enbek.kz/" title="" class="icon-instagram" target="_blank"> </a></li>
                    <li><a href="https://twitter.com/enbekkz" title="" class="icon-twitter" target="_blank"> </a></li>
                    <li><a href="https://www.facebook.com/www.enbek.kz/" title="" class="icon-facebook" target="_blank"> </a></li>
                    <li><a href="https://vk.com/enbekkz" title="" class="icon-vk" target="_blank"> </a></li>
                </ul>
                <div class="apps-links">
                    <a href="#" target="_blank" title="Перейти"><img src="/assets/img/appstore.png" alt=""></a>
                    <a href="#" target="_blank" title="Перейти"><img src="/assets/img/playmarket.png" alt=""></a>
                </div>
            </div>
        </div>
    </div>
</footer>

