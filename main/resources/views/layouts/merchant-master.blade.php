<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://fonts.googleapis.com/css?family=Fira+Sans:400,500,600,700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/basicsite/assets/css/reset.css">
        <link rel="stylesheet" href="/basicsite/assets/css/unsemantic-grid-responsive-tablet.css">
        <link rel="stylesheet" href="/basicsite/assets/css/custom2.css">
        <title>Capital X | Merchant Pay</title>
        @yield('customCSS')
    </head>

    <body id="auth">
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
