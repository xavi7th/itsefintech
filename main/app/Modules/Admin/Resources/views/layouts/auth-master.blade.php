<!doctype html>
<html lang="{{ app()->getLocale() }}">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="robots" content="noindex,nofollow">
        <title>{{ env('APP_TITLE') }}</title>

        <style>
            .preloader {
                position: fixed;
                left: 0;
                top: 0;
                bottom: 0;
                right: 0;
                z-index: 10000;
                display: flex;
                justify-content: center;
                align-items: center;
                padding: 20px;
                background: #24adef;
                transition: .3s all ease;
            }

            .preloader-body {
                text-align: center;
            }

            .preloader-body p {
                position: relative;
                right: -8px;
            }

            .cssload-container {
                width: 100%;
                height: 36px;
                text-align: center;
            }

            .cssload-speeding-wheel {
                width: 36px;
                height: 36px;
                margin: 0 auto;
                border: 3px solid #fff;
                border-radius: 50%;
                border-left-color: transparent;
                border-bottom-color: transparent;
                animation: cssload-spin .88s infinite linear;
            }

            .loader-img {
                height: 100px;
                display: block;
                margin: 0 auto 15px;
            }

            .preloader-body span {
                font-weight: bold;
                color: white;
                font-size: larger;
            }

            @-webkit-keyframes cssload-spin {
                100% {
                    transform: rotate(360deg);
                }
            }

            @keyframes cssload-spin {
                100% {
                    transform: rotate(360deg);
                }
            }

        </style>

        @yield('customCSS')
    </head>

    <body>

        <div class="preloader">
            <div class="preloader-body">
                <p>
                    <img src="/img/logo-round-small.png" alt="Amju Unique Logo" class="loader-img" />
                    <span>Admin Panel Loading ...</span>
                </p>
                <p></p>
                <div class="cssload-container">
                    <div class="cssload-speeding-wheel"></div>
                </div>
            </div>
        </div>


        <div id="auth-app">
            @yield('contents')
        </div>

        <script src="{{ mix('js/admin-app-vendor.js') }}"></script>
        <script src="{{ mix('js/manifest.js') }}"></script>
        <script src="{{ mix('js/vendor.js') }}"></script>
        <script src="{{ mix('js/admin-auth-app.js') }}"></script>

        @yield('customJS')
    </body>

</html>
