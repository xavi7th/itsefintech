@extends('layouts.merchant-master')
@section('contents')
<div class="grid-40 mobile-grid-100 push-30 content-container">
    <div id="welcome-title">
        <h1 style="font-weight: 400;" class="pending">Transaction in progress...</h1>
        <h1 style="font-weight: 400; display:none;" class="success">Merchant Pay Transaction Successful</h1>
        <h1 style="font-weight: 400; display:none;" class="failed">Merchant Pay Transaction Failed</h1>
    </div>
    <br>
    <img src="/basicsite/assets/img/pending.png" alt="" style="width: 50%; display: block; margin: auto;"
        class="pending">
    <img src="/basicsite/assets/img/success.png" alt="" style="width: 50%; display: none; margin: auto;"
        class="success">
    <img src="/basicsite/assets/img/error.png" alt="" style="width: 50%; display: none; margin: auto;" class="failed">
    <br>
    <br>
    <div id="closing-title">
        <p>Merchant Pay Powered by</p>
        <img src="/basicsite/assets/img/logodark.png" alt="">
    </div>
</div>

@endsection

@section('customJS')
<script>
    $( function () {

        setTimeout( () => {
            var $shown = $( '.pending' );
            // var $hidden = $( '.success' );
            var $hidden = $( '.failed' );
            $shown.fadeOut( 600, function () {
                $hidden.addClass( 'show' ).fadeIn( 600, function () {
                    $shown.removeClass( 'show' ).addClass( 'hide' )
                } ).removeClass( 'hide' )
            } )

        }, 3000 );
    } )

</script>
@endsection

@section('customCSS')
<script>
    window.jQuery ||
        document.write( '<script src="/basicsite/assets/js/vendor/jquery-3.4.1.min.js"><\/script>' );

</script>
<style>
    #closing-title {
        position: fixed;
        width: 100%;
        bottom: 17px;
        left: 0;
    }

    @media (max-width: 767px) {
        #closing-title {
            position: absolute;
        }

        /* .auth-header {
            display: none !important;
        } */
    }

    .content-container {
        margin-top: 5em;
        text-align: center;

    }

    @media (max-width: 767px) {
        ☄.content-container {
            margin-top: 1em !important;
        }
    }

</style>
@endsection
