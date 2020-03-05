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
        <form id="validate-merchant-code-form" action="/validate-merchant-code" method="post">
            {{-- {{ csrf_field() }} --}}
            <div class="grid-100">
                <input class="form-data" id="merchant-id" type="text" name="merchant_id">
            </div>
            <div class="grid-100">
                <input class="form-data" id="validate-merchant-code" type="submit" value="Proceed">
            </div>
        </form>
    </div>
    <div class="form-block voucher-code hide" style="display:none;">
        <form action="">
            <div class="grid-100">
                <input class="form-data" id="voucher-code" type="text" name="merchant_id"
                    placeholder="Enter Voucher Code">
            </div>
            <div class="grid-100">
                <input class="form-data" id="trans-amount" type="number" name="merchant_id"
                    placeholder="Enter Transaction Amount">
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
    let token = document.head.querySelector( 'meta[name="csrf-token"]' )
    if ( token ) {
        window.axios.defaults.headers.common[ 'X-CSRF-TOKEN' ] = token.content
        var larToken = token.content
    }


    $( function () {


        var switchScreen = function () {
            var $shown = $( '.show' );
            $shown.slideUp( 600, function () {
                $( '.hide' ).addClass( 'show' ).slideDown( 600, function () {
                    $shown.removeClass( 'show' ).addClass( 'hide' )
                } ).removeClass( 'hide' )
            } )
        }
        $( '#validate-merchant-code' ).click( function ( e ) {
            e.preventDefault();

            axios.post( '/validate-merchant-code', {
                    merchant_code: $( '#merchant-id' ).val(),
                } )
                .then( function ( response ) {
                    if ( response.status == 204 ) {
                        Swal.fire( {
                            icon: 'success',
                            title: 'Logged in successfully',
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            onOpen: ( toast ) => {
                                toast.addEventListener( 'mouseenter', Swal.stopTimer )
                                toast.addEventListener( 'mouseleave', Swal.resumeTimer )
                            }
                        } )
                        switchScreen();

                    }
                } )
                .catch( function ( error ) {
                    if ( error.response ) {
                        // The request was made and the server responded with a status code
                        // that falls out of the range of 2xx
                        console.log( error.response );
                        Swal.fire( {
                            icon: 'error',
                            title: error.response.statusText,
                            text: error.response.data,
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 7000,
                            timerProgressBar: true,
                            onOpen: ( toast ) => {
                                toast.addEventListener( 'mouseenter', Swal.stopTimer )
                                toast.addEventListener( 'mouseleave', Swal.resumeTimer )
                            }
                        } )
                    } else if ( error.request ) {
                        // The request was made but no response was received
                        // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
                        // http.ClientRequest in node.js
                        console.log( error.request );
                    } else {
                        // Something happened in setting up the request that triggered an Error
                        console.log( 'Error', error.message );
                    }

                } );

        } )

        $( '#process-merchant-transaction' ).click( function ( e ) {
            e.preventDefault();

            axios.post( '/merchant-transaction/create', {
                    amount: $( '#trans-amount' ).val(),
                    voucher_code: $( '#voucher-code' ).val(),
                    merchant_code: $( '#merchant-id' ).val(),
                } )
                .then( function ( response ) {
                    if ( response.status == 201 ) {
                        Swal.fire( {
                            icon: 'info',
                            title: 'Voucher debit processing...',
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            onOpen: ( toast ) => {
                                toast.addEventListener( 'mouseenter', Swal.stopTimer )
                                toast.addEventListener( 'mouseleave', Swal.resumeTimer )
                            }
                        } ).then( rsp => {
                            location.href = '/process-merchant-transaction/' + response.data.id
                        } )
                    }
                } )
                .catch( function ( error ) {
                    if ( error.response ) {
                        // The request was made and the server responded with a status code
                        // that falls out of the range of 2xx
                        console.log( error.response );
                        Swal.fire( {
                            icon: 'error',
                            title: error.response.statusText,
                            text: error.response.data,
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 7000,
                            timerProgressBar: true,
                            onOpen: ( toast ) => {
                                toast.addEventListener( 'mouseenter', Swal.stopTimer )
                                toast.addEventListener( 'mouseleave', Swal.resumeTimer )
                            }
                        } )
                    } else if ( error.request ) {
                        // The request was made but no response was received
                        // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
                        // http.ClientRequest in node.js
                        console.log( error.request );
                    } else {
                        // Something happened in setting up the request that triggered an Error
                        console.log( 'Error', error.message );
                    }

                } );

        } )
    } )

</script>
@endsection

@section('customCSS')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
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
