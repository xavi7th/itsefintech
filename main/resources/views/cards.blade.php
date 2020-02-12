@extends('layouts.master')
@section('contents')


<!--start slider section-->
<section id="hero" class="hero-content hero-slider-section-two section-lg"
    style="padding-top: 25rem; background: url('/basicsite/img/hero-section-bg-4.jpg')no-repeat center center / cover">
    <div class="hero-slider-wrap" style="position: relative; bottom: 230px;">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <div class="pt-100 hero-content-slider hero-content owl-carousel owl-theme white-indicator">
                        <div class="item">
                            <div class="hero-content">
                                <h1>Titanium Black Card</h1>
                                <p>Live better, not harder. This card gives you more to life with up to
                                    NGN500,000 credit limit. Enjoy rewards when you spend. Shop, eat,
                                    travel and even
                                    watch movies all on credit. What better way to live your life?</p>
                            </div>
                        </div>
                        <div class="item">
                            <div class="hero-content">
                                <h1>Titanium Premium Card</h1>
                                <p>Running a business shouldn’t be that hard. Get a card that does all
                                    the
                                    work while you focus on what really matters. Get up to NGN2,000,000.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 hidden-xs">
                    <div class="hero-image zindex" style="top: 4rem;">
                        <img src="/basicsite/img/faq-right.png" alt="hero-image" class="img-responsive">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section-shape">
        <img src="/basicsite/img/wave-shap.png" alt="shape image">
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
                        <p><strong>Info!</strong> Trusted by the world’s most innovative company.</p>
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

<!--start promo section-->
<section class="promo-section ptb-90">
    <div class="promo-section-wrap">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <div class="bg-secondary single-promo-section text-center">
                        <div class="single-promo-content">
                            <span class="ti-face-smile  "></span>
                            <h6>Easy To Use</h6>
                            <p>Lorem ipsum dolor sit amet, eam ex probo tation tractatos. Ut vel hinc
                                solet
                                tincidunt.</p>
                        </div>
                        <div class="line"></div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="bg-secondary single-promo-section text-center">
                        <div class="single-promo-content">
                            <span class="ti-vector"></span>
                            <h6>Awesome Design</h6>
                            <p>Lorem ipsum dolor sit amet, eam ex probo tation tractatos. Ut vel hinc
                                solet
                                tincidunt.</p>
                        </div>
                        <div class="line"></div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="bg-secondary single-promo-section text-center">
                        <div class="single-promo-content">
                            <span class="ti-palette"></span>
                            <h6>Easy To Customize</h6>
                            <p>Lorem ipsum dolor sit amet, eam ex probo tation tractatos. Ut vel hinc
                                solet
                                tincidunt.</p>
                        </div>
                        <div class="line"></div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="bg-secondary single-promo-section text-center">
                        <div class="single-promo-content">
                            <span class="ti-headphone-alt"></span>
                            <h6>24/7 Support</h6>
                            <p>Lorem ipsum dolor sit amet, eam ex probo tation tractatos. Ut vel hinc
                                solet
                                tincidunt.</p>
                        </div>
                        <div class="line"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--end promo section-->


@endsection
