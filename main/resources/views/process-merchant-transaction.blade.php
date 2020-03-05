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
    let token = document.head.querySelector( 'meta[name="csrf-token"]' )
    if ( token ) {
        window.axios.defaults.headers.common[ 'X-CSRF-TOKEN' ] = token.content
        var larToken = token.content
    }
    $( function () {

        var checkTransStatus = setInterval( () => {
            axios.post( '/process-merchant-transaction/' + {{ $merchant_transaction->id}}, {
                    amount: $( '#trans-amount' ).val(),
                    voucher_code: $( '#voucher-code' ).val(),
                    merchant_code: $( '#merchant-id' ).val(),
                } )
                .then( function ( response ) {

                    if ( response.status == 201 ) {
                        Swal.fire( {
                            icon: 'success',
                            title: 'Transaction completed',
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

												clearInterval( checkTransStatus );

                        var $shown = $( '.pending' );
                        var $hidden = $( '.success' );
                        $shown.fadeOut( 600, function () {
                            $hidden.addClass( 'show' ).fadeIn( 600, function () {
                                $shown.removeClass( 'show' ).addClass( 'hide' )
                            } ).removeClass( 'hide' )
												} )

                    } else if ( response.status == 205 ) {
											Swal.fire( {
                            icon: 'warning',
                            title: 'Transaction cancelled by user!',
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 10000,
                            timerProgressBar: true,
                            onOpen: ( toast ) => {
                                toast.addEventListener( 'mouseenter', Swal.stopTimer )
                                toast.addEventListener( 'mouseleave', Swal.resumeTimer )
                            }
												} )

                        clearInterval( checkTransStatus );

                        var $shown = $( '.pending' );
                        var $hidden = $( '.failed' );
                        $shown.fadeOut( 600, function () {
                            $hidden.addClass( 'show' ).fadeIn( 600, function () {
                                $shown.removeClass( 'show' ).addClass( 'hide' )
                            } ).removeClass( 'hide' )
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


        }, 2000 );
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
