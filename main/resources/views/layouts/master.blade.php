<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!--title-->
        <title>Capital X</title>

        <!--favicon icon-->
        <link rel="icon" href="/basicsite/img/favicon.png" type="image/png" sizes="16x16">

        <!-- font-awesome css -->
        <link rel="stylesheet" href="/basicsite/css/font-awesome.min.css">

        <!--themify icon-->
        <link rel="stylesheet" href="/basicsite/css/themify-icons.css">

        <!-- magnific popup css-->
        <link rel="stylesheet" href="/basicsite/css/magnific-popup.css">

        <!--owl carousel -->
        <link href="/basicsite/css/owl.theme.default.min.css" rel="stylesheet">
        <link href="/basicsite/css/owl.carousel.min.css" rel="stylesheet">

        <!-- bootstrap core css -->
        <link href="/basicsite/css/bootstrap.min.css" rel="stylesheet">

        <!-- style css -->
        <link href="/basicsite/css/style.css" rel="stylesheet">

        <!-- responsive css -->
        <link href="/basicsite/css/responsive.css" rel="stylesheet">

        <script src="/basicsite/js/vendor/modernizr-2.8.1.min.js"></script>

        <!-- HTML5 Shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
    <script src="/basicsite/js/vendor/html5shim.js"></script>
    <script src="/basicsite/js/vendor/respond.min.js"></script>
    <![endif]-->

        <!-- custom css -->
        <link href="/basicsite/css/custom.css" rel="stylesheet">
        @yield('customCSS')

    </head>


    <body>

        <!-- Preloader -->
        <div id="preloader">
            <div id="loader"></div>
        </div>
        <!--end preloader-->
        <div id="main" class="main-content-wraper">
            <div class="main-content-inner">

                <!--start header section-->
                <header class="header">
                    <!--start navbar-->
                    <div class="navbar navbar-default navbar-fixed-top">
                        <div class="container">
                            <div class="row">
                                <div class="navbar-header page-scroll">
                                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                                        data-target="#myNavbar">
                                        <span class="sr-only">Toggle navigation</span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                    </button>
                                    <a class="navbar-brand page-scroll" href="/">
                                        <img src="/basicsite/img/logo.png" alt="logo" style="height: 100px;">
                                    </a>
                                </div>

                                <!-- Collect the nav links, forms, and other content for toggling -->
                                <div class="navbar-collapse collapse" id="myNavbar">
                                    <ul class="nav navbar-nav navbar-right" style="position: relative; top: 30px;">
                                        <li class="active"><a class="page-scroll" href="/#hero">Home</a></li>
                                        <li><a class="page-scroll" href="#features">Features</a></li>
                                        <li><a class="page-scroll" href="/merchant-pay">Merchant Pay</a></li>
                                        <li><a class="page-scroll" href="/cards">Cards</a></li>
                                        <li><a class="page-scroll" href="#faqs">FAQ</a></li>
                                        <li><a class="page-scroll" href="#contact">Contact</a></li>
                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!--end navbar-->
                </header>
                <!--end header section-->



                @yield('contents')

                <!--contact us section start-->
                <section id="contact" class="contact-us ptb-90">
                    <div class="contact-us-wrap">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="section-heading">
                                        <h3>Contact us</h3>
                                        <p>Capital X is reimagining the entire payment card system in Nigeria. We offer
                                            prepaid
                                            cards and credit services through
                                            our mobile app. Capital X is building the next generation of B2B and B2C
                                            financial
                                            services with better tech and without
                                            the restrictions of legacy technology.
                                        </p>
                                    </div>
                                    <div class="footer-address">
                                        <h6>Head Office</h6>
                                        <p>{{ config('app.address') }}</p>
                                        <ul>
                                            <li><i class="fa fa-phone"></i><span>Phone: {{ config('app.phone') }}</span>
                                            </li>
                                            <li><i class="fa fa-envelope-o"></i><span>Email: <a
                                                        href="mailto:{{ config('app.email') }}">{{ config('app.email') }}</a></span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <form action="/contact-us" method="POST" id="contactForm1" class="contact-us-form"
                                        novalidate="novalidate">
                                        {{ csrf_field() }}
                                        <h6>Reach us quickly
                                        </h6>
                                        <div class="row">
                                            <div class="col-sm-6 col-xs-12">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="name"
                                                        placeholder="Enter name" required="required">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-xs-12">
                                                <div class="form-group">
                                                    <input type="email" class="form-control" name="email"
                                                        placeholder="Enter email" required="required">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6 col-xs-12">
                                                <div class="form-group">
                                                    <input type="text" name="phone" value="" class="form-control"
                                                        id="phone" placeholder="Your Phone">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-xs-12">
                                                <div class="form-group">
                                                    <input type="text" name="company" value="" size="40"
                                                        class="form-control" id="company" placeholder="Your Company">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="form-group">
                                                    <textarea name="message" id="message" class="form-control" rows="7"
                                                        cols="25" placeholder="Message"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 mt-20">
                                                <button type="submit" class="btn softo-solid-btn pull-right"
                                                    id="btnContactUs">Send
                                                    Message
                                                </button></div>
                                        </div>
                                    </form>
                                    <p class="form-message"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!--contact us section end-->
                <!--start footer section-->
                <footer class="footer-section bg-secondary ptb-60">
                    <div class="footer-wrap">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6 col-md-offset-3">
                                    <div class="footer-single-col text-center">
                                        <!-- <img src="/basicsite/img/logo-color.png" alt="footer logo"> -->
                                        <div class="footer-social-list">
                                            <ul class="list-inline">
                                                <li><a href="#"><i class="fa fa-facebook"></i></a>
                                                </li>
                                                <li><a href="#"><i class="fa fa-twitter"></i></a>
                                                </li>
                                                <li><a href="#"><i class="fa fa-google-plus"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="copyright-text">
                                            <p>&copy;
                                                copyright
                                                <a href="#">Capital
                                                    X</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
                <!--end footer section-->

            </div>

        </div>



        <!--=========== all js file link ==============-->


        <!-- main jQuery -->
        <script src="/basicsite/js/jquery-3.3.1.min.js"></script>

        <!-- bootstrap core js -->
        <script src="/basicsite/js/bootstrap.min.js"></script>

        <!-- smothscroll -->
        <script src="/basicsite/js/jquery.easeScroll.min.js"></script>

        <!--owl carousel-->
        <script src="/basicsite/js/owl.carousel.min.js"></script>

        <!-- scrolling nav -->
        <script src="/basicsite/js/jquery.easing.min.js"></script>

        <!--fullscreen background video js-->
        <script src="/basicsite/js/jquery.mb.ytplayer.min.js"></script>

        <!--typed js -->
        <script src="/basicsite/js/typed.min.js"></script>

        <!--magnific popup js-->
        <script src="/basicsite/js/magnific-popup.min.js"></script>

        <!-- custom script -->
        <script src="/basicsite/js/scripts.js"></script>

        @yield('customJS')

    </body>

</html>
