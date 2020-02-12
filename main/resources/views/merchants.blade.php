@extends('layouts.master')
@section('contents')


<!--start slider section-->
<section id="hero" class="hero-content hero-slider-section-two section-lg"
    style="padding-top: 10rem; background: url('/basicsite/img/hero-section-bg-4.jpg')no-repeat center center / cover">
    <div class="hero-slider-wrap">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <div class="pt-100 hero-content-slider hero-content owl-carousel owl-theme white-indicator">
                        <div class="item">
                            <div class="hero-content">
                                <h1>MERCHANT PAY</h1>
                                <p>You don’t need cash to do the things you love. Shop groceries, travel
                                    anywhere, watch movies, go on a dinner or hail a ride on credit with
                                    our
                                    Merchant Pay feature.</p>
                            </div>
                        </div>
                        <div class="item">
                            <div class="hero-content">
                                <h1>MERCHANT PAY</h1>
                                <p> We have partnered with hundreds of
                                    merchants and businesses to give you access to the best services –
                                    all
                                    on credit!</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 hidden-xs">
                    <div class="hero-image">
                        <img src="/basicsite/img/app-hand-top.png" alt="hero-image" class="img-responsive">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section-shape">
        <img src="/basicsite/img/white-bottom.png" alt="shape image">
    </div>
</section>
<!--end slider section-->

<!--start customers section-->
<div class="customers-section pt-40">
    <div class="customers-wrap">
        <div class="container">
            <div class="row">
                <div class="col-md-5 col-sm-4">
                    <div class="higlight-text">
                        <p><strong>Info!</strong> Trusted by Merchants & businesses nationwide.</p>
                    </div>
                </div>
                <div class="col-md-7 col-sm-8">
                    <div class="customers-content">
                        <div class="owl-carousel owl-theme customers-slider">
                            <div class="item">
                                <img src="/basicsite/img/client/customer-logo-1.png" alt="client logo">
                            </div>
                            <div class="item">
                                <img src="/basicsite/img/client/customer-logo-2.png" alt="client logo">
                            </div>
                            <div class="item">
                                <img src="/basicsite/img/client/customer-logo-3.png" alt="client logo">
                            </div>
                            <div class="item">
                                <img src="/basicsite/img/client/customer-logo-1.png" alt="client logo">
                            </div>
                            <div class="item">
                                <img src="/basicsite/img/client/customer-logo-2.png" alt="client logo">
                            </div>
                            <div class="item">
                                <img src="/basicsite/img/client/customer-logo-3.png" alt="client logo">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end customers section-->

<!--start blog section -->
<section id="news" class="ptb-90">
    <div class="blog-section-wrap">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-heading text-center">
                        <h3>How does it work?</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div id="sliderBlog" class="owl-carousel">
                    <div class="single-blog-article">
                        <div class="blog-post-img-thumb">
                            <a href="blog-details.html" target="_blank">
                                <img src="/basicsite/img/blog-img1.jpg" alt="">
                            </a>
                        </div>
                        <div class="blog-post-content-area">
                            <h3><a href="blog-details.html" target="_blank">1. Get any of Capital X
                                    Card</a>
                            </h3>
                            <p>You can do this via the app or directly from our sales team.</p>
                        </div>
                    </div>

                    <div class="single-blog-article">
                        <div class="blog-post-img-thumb">
                            <a href="blog-details.html" target="_blank">
                                <img src="/basicsite/img/blog-img2.jpg" alt="">
                            </a>
                        </div>
                        <div class="blog-post-content-area">
                            <h3><a href="blog-details.html" target="_blank">2. Fund your Card</a></h3>
                            <p>Load your card and spend for a minimum of 14 days.</p>
                        </div>
                    </div>

                    <div class="single-blog-article">
                        <div class="blog-post-img-thumb">
                            <a href="blog-details.html" target="_blank">
                                <img src="/basicsite/img/blog-img3.jpg" alt="">
                            </a>
                        </div>
                        <div class="blog-post-content-area">
                            <h3><a href="blog-details.html" target="_blank">3. Qualify for a Merchant
                                    Credit</a></h3>
                            <p>We assign a merchant limit to your account. Spend on the things you love
                                all on CREDIT!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--end blog section -->

<!--start download section-->
<section id="merchant" class="download-section"
    style="background: url('/basicsite/img/download-bg.jpg')no-repeat center center fixed">
    <div class="container">
        <div class="row">
            <div class="col-md-7 col-sm-12">
                <div class="download-app-text">
                    <h3>Merchant's Pay</h3>
                    <p>Seamlessly transform timely e-commerce for diverse leadership skills.
                        Conveniently
                        reconceptualize go forward expertise without extensible applications.
                        Phosfluorescently.</p>
                    <br>
                    <form action="/api/v1/merchant-transaction/create" method="POST" id="merchantPayForm"
                        class="contact-us-form" novalidate="novalidate">

                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @elseif(session()->has('success'))
                        <div class="alert alert-success">
                            <ul>
                                <li>{{ session('success') }}</li>
                            </ul>
                        </div>
                        @endif

                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="merchant_code"
                                        placeholder="Enter Merchant Code" required="required">
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="voucher_code"
                                        placeholder="Enter Voucher code" required="required">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="amount" placeholder="Enter Amount"
                                        required="required">
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input type="email" class="form-control" name="description"
                                        placeholder="Description">
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="download-app-button" style="padding-top: 0px;">
                        <a href="#" class="download-btn" id="processPayment">
                            <span class="ti-credit-card"></span>
                            <p>
                                <small>Click here</small>
                                <br>Process Payment
                            </p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--end download section-->

@endsection

@section('customJS')
<script>
    $( '#processPayment' ).click( function ( e ) {
        e.preventDefault();
        $( '#merchantPayForm' ).submit();
    } )

</script>
@endsection
