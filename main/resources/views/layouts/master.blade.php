<!doctype html>
<html lang="en">

    <head>

      <!-- Google Tag Manager -->
      <script>
          ( function ( w, d, s, l, i ) {
              w[ l ] = w[ l ] || [];
              w[ l ].push( {
                  'gtm.start': new Date().getTime(),
                  event: 'gtm.js'
              } );
              var f = d.getElementsByTagName( s )[ 0 ],
                  j = d.createElement( s ),
                  dl = l != 'dataLayer' ? '&l=' + l : '';
              j.async = true;
              j.src =
                  'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
              f.parentNode.insertBefore( j, f );
          } )( window, document, 'script', 'dataLayer', 'GTM-M9N8WNW' );

      </script>
      <!-- End Google Tag Manager -->

      <!-- Global site tag (gtag.js) - Google Analytics -->
      <script async src="https://www.googletagmanager.com/gtag/js?id=G-95XMCCXT9S"></script>
      <script>
          window.dataLayer = window.dataLayer || [];

          function gtag() {
              dataLayer.push( arguments );
          }
          gtag( 'js', new Date() );

          gtag( 'config', 'G-95XMCCXT9S' );

      </script>

      <meta charset="utf-8">
      <title>Capital X - Premium Banking, Personalized Convenience</title>
      <base href="{{route('app.home')}}">

      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <link rel="apple-touch-icon" href="/img/apple-touch-icon.png">
      <link rel="shortcut icon" href="/img/favicon.png" type="image/png">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

      <script src="{{ asset('basicsite/js/jquery-3.4.1.min.js') }}"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>

      <meta name="theme-color" content="#fafafa" />

      <link rel="stylesheet" href="{{ asset('basicsite/css/custom.css') }}" />
      <link rel="stylesheet" href="{{ asset('basicsite/css/shorthand.min.css') }}" />
      <link rel="stylesheet" href="{{ asset('basicsite/css/slick-theme.css') }}" />
      <link rel="stylesheet" href="{{ asset('basicsite/css/slick.min.css') }}" />

      <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon">

    </head>

    <body class="bg-black muli">
      <!-- Google Tag Manager (noscript) -->
      <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-M9N8WNW" height="0" width="0"
              style="display:none;visibility:hidden"></iframe></noscript>
      <!-- End Google Tag Manager (noscript) -->

      <nav class="w-100pc flex flex-column md-flex-row md-px-10 py-5 bg-black">
        <div class="flex justify-between">
          <a href="index.html" class="flex items-center p-2 mr-4 no-underline">
            <img class=" w-auto mx-3" src="{{ asset('basicsite/images/logo1.png') }}" />
          </a>
          <a data-toggle="toggle-nav" data-target="#nav-items" href="#" class="flex items-center ml-auto md-hidden red-lighter opacity-50 hover-opacity-50 ease-300 p-1 m-3">
            <i data-feather="menu"></i>
            <i class="fa fa-bars"></i>
          </a>
        </div>
        <div id="nav-items" class="hidden flex sm-w-100pc flex-column md-flex md-flex-row md-justify-end items-center">
          <a href="{{ route('app.home') }}" class="fs-s1 mx-3 py-3 white no-underline hover-underline">Home</a>
          <a href="#features" class="fs-s1 mx-3 py-3 white no-underline hover-underline">Features</a>

          <a href="{{ route('app.cards') }}" class="fs-s1 mx-3 py-3 white no-underline hover-underline">Cards</a>
          <a href="#premium" class="fs-s1 mx-3 py-3 white no-underline hover-underline">Premium</a>
          <a href="#testimonials" class="fs-s1 mx-3 py-3 white no-underline hover-underline">Testimonial</a>
          <a href="#faq" class="fs-s1 mx-3 py-3 white no-underline hover-underline">FAQs</a>
          <a href="{{ route('app.savings') }}" class="button bg-red white fw-600 no-underline mx-5">Savings</a>
        </div>
      </nav>

      @yield('contents')


      <section class="p-10 md-p-l5">
        <div class="br-8 bg-red-lightest-10 p-5 md-p-l5 flex flex-wrap md-justify-between md-items-center">
          <div class="w-100pc md-w-33pc">
            <h2 class="white fs-m4 fw-800">Newsletter</h2>
            <p class="fw-600 red-lightest opacity-40">Be the first to see exclusive deals and benefits from us.</p>
          </div>
          <div class="w-100pc md-w-50pc">
            <div class="flex my-5">
              <input type="text" class="input-lg flex-grow-1 bw-0 fw-200 bg-red-lightest-10 white ph-red-lightest focus-white opacity-80 fs-s3 py-5 br-r-0" placeholder="Email Address">
              <button class="button-lg bg-red red-lightest fw-300 fs-s3 br-l-0">Get Started</button>
            </div>
          </div>
        </div>
      </section>

      <footer class="p-5 md-p-l5 bg-red-lightest-10">
        <div class="flex flex-wrap">
          <div class="md-w-25pc mb-10">
            <img src="{{ asset('basicsite/images/logo1.png') }}" class="w-l5" alt="">
            <div class="white opacity-70 fs-s2 mt-4 md-pr-10">
              <p>Capital X is a premium digital bank. We are not just reimagining the entire banking and payment system in Nigeria, we are redefining what convenience means and reshaping how people pay for services. </p>
              <br>
              <p>All deposits are domiciled with our CBN licensed banking partners and safely insured by NDIC.</p>
              <a href="https://play.google.com/store/apps/details?id=com.doublesouth.victor_app"><img style="height: 60px;" src="{{ asset('basicsite/images/playstore.png') }}"></a>
            </div>
          </div>
          <div class="w-100pc md-w-50pc">
            <div class="flex justify-around">
              <div class="w-33pc md-px-10 mb-10">
                <h5 class="white">Company</h5>
                <ul class="list-none mt-5 fs-s2">
                  <li class="my-3"><a href="" class="white opacity-70 no-underline hover-underline">About
                    Us</a></li>
                  <li class="my-3"><a href="{{ route('app.career') }}" class="white opacity-70 no-underline hover-underline">Jobs</a>
                  </li>
                  <li class="my-3"><a href="{{ route('app.terms') }}" class="white opacity-70 no-underline hover-underline">Terms & Conditions</a></li>
                  <li class="my-3"><a href="{{ route('app.credit_policy') }}" class="white opacity-70 no-underline hover-underline">Credit Policy</a>
                  </li>
                </ul>
              </div>
              <div class="w-33pc md-px-10 mb-10">
                <h5 class="white">Product</h5>
                <ul class="list-none mt-5 fs-s2">
                  <li class="my-3"><a href="#" class="white opacity-70 no-underline hover-underline">Expense Cards</a></li>
                  <li class="my-3"><a href="#" class="white opacity-70 no-underline hover-underline">Premium Banking</a>
                  </li>
                  <li class="my-3"><a href="#" class="white opacity-70 no-underline hover-underline">Savings</a></li>
                  <li class="my-3"><a href="https://getmerchantpay.com" class="white opacity-70 no-underline hover-underline">Merchant Pay</a>
                  </li>
                </ul>
              </div>
              <div class="w-33pc md-px-10 mb-10">
                <h5 class="white">Contact</h5>
                <ul class="list-none mt-5 fs-s2">
                  <li class="my-3 white opacity-70">support@capitalx.cards</li>
                  <li class="my-3 white opacity-70">09043482725
                  </li>
                  <li class="my-3 white opacity-70">1A Hughes Avenue, Yaba 100001, Lagos State.</li>
                  <li class="my-3"><a href="#" class="white opacity-70 no-underline hover-underline"></a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="w-100pc md-w-25pc">
            <div class="flex w-75pc md-w-100pc mx-auto">
              <input type="text" class="input flex-grow-1 bw-0 fw-200 bg-red-lightest-10 white ph-red-lightest focus-white opacity-80 fs-s3 py-5 br-r-0" placeholder="Email Address">
              <button class="button bg-red red-lightest fw-300 fs-s3 br-l-0">Start</button>
            </div>
            <div class="flex justify-around my-8">
              <a href="https://twitter.com/capitalx_cards" class="relative p-5 bg-red br-round white hover-scale-up-1 ease-400"><i data-feather="twitter" class="absolute-center h-4"></i></a>
              <a href="https://facebook.com/capitalxcards" class="relative p-5 bg-red br-round white hover-scale-up-1 ease-400"><i data-feather="facebook" class="absolute-center h-4"></i></a>
              <a href="https://instagram.com/capitalxcards" class="relative p-5 bg-red br-round white hover-scale-up-1 ease-400"><i data-feather="instagram" class="absolute-center h-4"></i></a>
            </div>
          </div>
        </div>
      </footer>


      <script src="https://js.paystack.co/v1/inline.js" async></script>
      <script src="https://www.google-analytics.com/analytics.js" async></script>


      <script src="{{ asset('basicsite/js/feather.min.js') }}"></script>
      <script src="{{ asset('basicsite/js/slick.min.js') }}"></script>
      <script  src="{{ asset('basicsite/js/smooth-scroll.polyfills.min.js') }}"></script>
      <script src="{{ asset('basicsite/js/script.js') }}"></script>
      <script src="{{ asset('basicsite/js/mobster.js') }}"></script>
      <script src="{{ asset('basicsite/js/bootstrap.bundle.min.js') }}"></script>
    </body>

</html>
