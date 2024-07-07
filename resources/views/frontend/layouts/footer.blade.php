<style>
    .wrapper>* {
        flex: 0 0 auto;
    }

    a {
        text-decoration: none;
        background-color: transparent;
    }

    .py-8 {
        padding-top: 1.5rem !important;
        padding-bottom: 1.5rem !important;
    }

    .footer-link-01 li+li {
        padding-top: 0.8rem;
    }

    .footer-title-01 {
        font-size: 16px;
        margin: 0 0 20px;
        font-weight: 600;
        color: white;
    }

    .footer-link-01 li+li {
        padding-top: .8rem;
    }

    @media (max-width: 991.98px) {
        .footer-link-01 li+li {
            padding-top: .6rem;
        }
    }

    .footer-link-01 a {
        position: relative;
        display: inline-block;
        vertical-align: top;
        color: white;
    }

    .footer-link-01 a:after {
        content: "";
        position: absolute;
        bottom: 0;
        left: auto;
        right: 0;
        width: 0;
        height: 1px;
        transition: ease all .35s;
        background: currentColor;
    }

    .footer-link-01 a:hover:after {
        left: 0;
        right: auto;
        width: 100%;
    }
</style>
<footer class="footer mt-5" style="background-color: #be3c3a;">
    <div class="footer-top py-8">
        <div class="container">
            <div class="row gy-5">
                <div class="col-lg-8 pe-xxl-10">
                    <div class="row gy-5">
                        <div class="col-6 col-lg-4">
                            <h5 class="footer-title-01">Help Links</h5>
                            <ul class="list-unstyled footer-link-01 m-0">
                                <li><a href="#">About Us</a></li>
                                <li><a href="#">Seller</a></li>
                            </ul>
                        </div>
                        <div class="col-6 col-lg-4">
                            <h5 class="footer-title-01">About</h5>
                            <ul class="list-unstyled footer-link-01 m-0">
                                <li><a href="#">Customer Service</a></li>
                                <li><a href="#">Terms &amp; Conditions</a></li>
                                <li><a href="#">Terms of Use</a></li>
                            </ul>
                        </div>
                        <div class="col-6 col-lg-4">
                            <h5 class="footer-title-01">Need Help?</h5>
                            <ul class="list-unstyled footer-link-01 m-0">
                                <li><a href="#">Contact Us</a></li>
                                <li><a href="#">FAQs</a></li>
                                <li><a href="#">Office location</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31682.67907939538!2d112.41214885606793!3d-6.96976150396464!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e77e5f9b7a6e6c5%3A0x81c87ce8849cc44b!2sLowayu%2C%20Kec.%20Dukun%2C%20Kabupaten%20Gresik%2C%20Jawa%20Timur!5e0!3m2!1sid!2sid!4v1719852270727!5m2!1sid!2sid"
                        width="400" height="200" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom small py-3 border-top border-white border-opacity-10">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-md-start py-1">
                    <p class="m-0 text-white text-opacity-75">© 2024 copyright by <a class="text-reset"
                            href="#">UMKM Lowayu</a></p>
                </div>
            </div>
        </div>
    </div>
</footer>

{{-- @php
    $footerInfo = Cache::rememberForever('footer_info', function(){
            return \App\Models\FooterInfo::first();
    });
    $footerSocials = Cache::rememberForever('footer_socials', function(){
        return \App\Models\FooterSocial::where('status', 1)->get();
    });
    $footerGridTwoLinks = Cache::rememberForever('footer_grid_two', function(){
        return \App\Models\FooterGridTwo::where('status', 1)->get();
    });
    $footerTitle = \App\Models\FooterTitle::first();
    $footerGridThreeLinks =Cache::rememberForever('footer_grid_three', function(){
        return \App\Models\FooterGridThree::where('status', 1)->get();
    });
@endphp
<footer class="footer_2">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-xl-3 col-sm-7 col-md-6 col-lg-3">
                <div class="wsus__footer_content">
                    <a class="wsus__footer_2_logo" href="{{url('/')}}">
                        <img src="{{asset(@$footerInfo->logo)}}" alt="logo">
                    </a>
                    <a class="action" href="callto:{{@$footerInfo->phone}}"><i class="fas fa-phone-alt"></i>{{@$footerInfo->phone}}</a>
                    <a class="action" href="mailto:{{@$footerInfo->email}}"><i class="far fa-envelope"></i>{{@$footerInfo->email}}</a>
                    <p><i class="fal fa-map-marker-alt"></i> {{@$footerInfo->address}}</p>
                    <ul class="wsus__footer_social">
                        @foreach ($footerSocials as $link)
                        <li><a class="behance" href="{{$link->url}}"><i class="{{$link->icon}}"></i></a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-xl-2 col-sm-5 col-md-4 col-lg-2">
                <div class="wsus__footer_content">
                    <h5>{{$footerTitle->footer_grid_two_title}}</h5>
                    <ul class="wsus__footer_menu">
                        @foreach ($footerGridTwoLinks as $link)
                            <li><a href="{{$link->url}}"><i class="fas fa-caret-right"></i> {{$link->name}}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-xl-2 col-sm-5 col-md-4 col-lg-2">
                <div class="wsus__footer_content">
                    <h5>{{$footerTitle->footer_grid_three_title}}</h5>
                    <ul class="wsus__footer_menu">
                        @foreach ($footerGridThreeLinks as $link)
                            <li><a href="{{$link->url}}"><i class="fas fa-caret-right"></i> {{$link->name}}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-xl-4 col-sm-7 col-md-8 col-lg-5">
                <div class="wsus__footer_content wsus__footer_content_2">
                    <h3>Subscribe To Our Newsletter</h3>
                    <p>Get all the latest information on Events, Sales and Offers.
                        Get all the latest information on Events.</p>
                    <form action="" method="POST" id="newsletter">
                        @csrf
                        <input type="text" placeholder="Email" name="email" class="newsletter_email">
                        <button type="submit" class="common_btn subscribe_btn">subscribe</button>
                    </form>
                    <div class="footer_payment">
                        <p>We're using safe payment for :</p>
                        <img src="{{asset('frontend/images/credit2.png')}}" alt="card" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="wsus__footer_bottom">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="wsus__copyright d-flex justify-content-center">
                        <p>{{@$footerInfo->copyright}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

 --}}
