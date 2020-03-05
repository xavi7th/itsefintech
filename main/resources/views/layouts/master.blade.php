<!doctype html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title>Capital X</title>
        <base href="/">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="apple-touch-icon" href="/img/apple-touch-icon.png">
        <link rel="stylesheet" href="/basicsite/assets/css/bootstrap.css" />
        <link rel="shortcut icon" href="/img/favicon.png" type="image/png">
        <link rel="stylesheet" href="/basicsite/assets/css/normalize.css" />
        <link rel="stylesheet" href="/basicsite/assets/css/main.css" />
        <link rel="stylesheet" href="/basicsite/assets/css/media.css" />

        <meta name="theme-color" content="#fafafa" />
        <link rel="stylesheet" href="/basicsite/styles.7ef82e31cf2e65289b57.css">
    </head>

    <body>
        <app-root></app-root>

        <script src="/basicsite/assets/js/vendor/modernizr-3.8.0.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script src="https://js.paystack.co/v1/inline.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
        </script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
        </script>
        <script>
            window.jQuery ||
                document.write(
                    '<script src="/basicsite/assets/js/vendor/jquery-3.4.1.min.js"><\/script>'
                );

        </script>
        <script src="/basicsite/assets/js/plugins.js"></script>
        <script src="/basicsite/assets/js/main.js"></script>
        <script>
            //Mobile Menu
            $( '.cp-mobile--menu__toggler' ).click( function () {
                $( this ).toggleClass( 'active' );
                $( '.cp-mobile--menu' ).toggleClass( 'open' );
            } );

            //Register Form Step
            $( ".next-button" ).on( "click", function ( e ) {
                e.preventDefault();
                $( this )
                    .closest( ".cp-card-form" )
                    .find( ".show-step" )
                    .removeClass( "show-step" ).next().addClass( "show-step" );
            } );
            $( ".previous-button" ).on( "click", function ( e ) {
                e.preventDefault();
                $( this )
                    .closest( ".cp-card-form" )
                    .find( ".show-step" )
                    .removeClass( "show-step" ).prev().addClass( "show-step" );
            } );


            $( window ).on( 'load', function () {
                $( '.cp-nav-links' ).append( '<li><a href="/merchant-login">Merchant Login</a></li>' )
            } )

        </script>

        <!-- Google Analytics: change UA-XXXXX-Y to be your site's ID. -->
        <script>
            window.ga = function () {
                ga.q.push( arguments );
            };
            ga.q = [];
            ga.l = +new Date();
            ga( "create", "UA-XXXXX-Y", "auto" );
            ga( "set", "transport", "beacon" );
            ga( "send", "pageview" );

        </script>
        <script src="https://www.google-analytics.com/analytics.js" async></script>
        <script src="/basicsite/runtime-es2015.0811dcefd377500b5b1a.js" type="module"></script>
        <script src="/basicsite/runtime-es5.0811dcefd377500b5b1a.js" nomodule defer></script>
        <script src="/basicsite/polyfills-es5.e94ba732172de6683982.js" nomodule defer></script>
        <script src="/basicsite/polyfills-es2015.442fa3cc45520cc0d518.js" type="module"></script>
        <script src="/basicsite/main-es2015.a72b5dd0cf337053bc5f.js" type="module"></script>
        <script src="/basicsite/main-es5.a72b5dd0cf337053bc5f.js" nomodule defer></script>
    </body>

</html>
