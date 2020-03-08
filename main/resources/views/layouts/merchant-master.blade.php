<!DOCTYPE html>
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

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://fonts.googleapis.com/css?family=Fira+Sans:400,500,600,700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/basicsite/assets/css/reset.css">
        <link rel="stylesheet" href="/basicsite/assets/css/unsemantic-grid-responsive-tablet.css">
        <link rel="stylesheet" href="/basicsite/assets/css/custom2.css">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Capital X | Merchant Pay</title>
        @yield('customCSS')
    </head>

    <body id="auth">
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-M9N8WNW" height="0" width="0"
                style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->

        <header class="auth-header">
            <div class="grid-60 tablet-grid-60 mobile-grid-75">
                <nav>
                    @if(!Route::is('merchants.login'))
                    <ul>
                        <li><a href="{{ route('merchants.login') }}">Back</a></li>
                    </ul>
                    @endif
                </nav>
            </div>
        </header>
        <section class="auth-section">
            <div class="grid-container">
                @yield('contents')
            </div>
        </section>



        @yield('customJS')

    </body>

</html>
