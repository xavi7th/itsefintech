@extends('layouts.merchant-master')
@section('contents')
<div class="grid-40 mobile-grid-100 push-30">
    <div id="welcome-title" class="merchant-code">
        <img src="/img/logo.png" alt="">
        <h1>Merchant Pay</h1>
        <p class="show">Enter your Merchant ID to access your transaction dashboard</p>
        <p class="hide" style="display:none">Enter the voucher code and transaction amount</p>
    </div>
    <div class="form-block merchant-code show">
        <h1 class="title">Enter your Merchant ID</h1>
        <form action="">
            <div class="grid-100">
                <input class="form-data" type="text" name="merchant_id">
            </div>
            <div class="grid-100">
                <input class="form-data" id="validate-merchant-code" type="submit" value="Proceed">
            </div>
        </form>
    </div>
    <div class="form-block voucher-code hide" style="display:none;">
        <form action="">
            <div class="grid-100">
                <input class="form-data" type="text" name="merchant_id" placeholder="Enter Voucher Code">
            </div>
            <div class="grid-100">
                <input class="form-data" type="number" name="merchant_id" placeholder="Enter Transaction Amount">
            </div>
            <div class="grid-100">
                <input class="form-data" id="process-merchant-transaction" type="submit" value="Pay">
            </div>
        </form>
    </div>
    <div id="closing-title">
        <p>Merchant Pay Powered by</p>
        <img src="/img/logodark.png" alt="">
    </div>
</div>

@endsection

@section('customJS')
<script>
    $( function () {
        $( '#validate-merchant-code' ).click( function ( e ) {
            e.preventDefault();
            var $shown = $( '.show' );
            $shown.slideUp( 600, function () {
                $( '.hide' ).addClass( 'show' ).slideDown( 600, function () {
                    $shown.removeClass( 'show' ).addClass( 'hide' )
                } ).removeClass( 'hide' )
            } )


        } )
        $( '#process-merchant-transaction' ).click( function ( e ) {
            e.preventDefault();
            var $shown = $( '.show' );
            var $hidden = $( '.hide' );
            $shown.slideUp( 600, function () {
                $hidden.addClass( 'show' ).slideDown( 600, function () {
                    $shown.removeClass( 'show' ).addClass( 'hide' )
                    $hidden.removeClass( 'hide' )
                } )
            } )


        } )
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
        ☄#closing-title {
            position: absolute;
        }

        .auth-header {
            display: none !important;
        }
    }

    #process-merchant-transaction,
    #validate-merchant-code {
        background-color: #eb3237;
        color: #fff;
        font-family: 'Fira Sans', sans-serif;
        cursor: pointer;
        border-radius: 4px;
        font-size: 12px;
        margin-bottom: 0;
    }

</style>
@endsection
