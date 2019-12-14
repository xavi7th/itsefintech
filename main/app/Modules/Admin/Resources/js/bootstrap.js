window._ = require( 'lodash' )
window.axios = require( 'axios' )
window.swal = require( 'sweetalert2' )

window.Toast = swal.mixin( {
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2000,
    icon: "success"
} );

window.BlockToast = swal.mixin( {
    showConfirmButton: false,
    showCloseButton: false,
    allowOutsideClick: false,
    allowEscapeKey: false
} );

window.swalPreconfirm = swal.mixin( {
    title: 'Are you sure?',
    text: "Implement this when you call the mixin",
    icon: 'question',
    showCloseButton: false,
    allowOutsideClick: () => !swal.isLoading(),
    allowEscapeKey: false,
    showCancelButton: true,
    focusCancel: true,
    cancelButtonColor: '#d33',
    confirmButtonColor: '#3085d6',
    confirmButtonText: 'To be implemented',
    showLoaderOnConfirm: true,
    preConfirm: () => {
        /** Implement this when you call the mixin */
    },
} )
/**
 * Implement this when you call the mixin
 * .then( ( result ) => {} );
 */


window.axios.defaults.headers.common[ 'X-Requested-With' ] = 'XMLHttpRequest'

window.axios.interceptors.response.use(
    res => {
        return res
    },
    err => {
        if ( err.response ) {
            // console.log( err.response )
            if ( err.response.status == 422 ) {
                var rawErrors = err.response.data.errors || err.response.data.message;

                if ( _.isString( rawErrors ) ) {
                    var errs = rawErrors
                } else if ( _.size( rawErrors ) == 1 ) {
                    var errs = _.reduce( rawErrors, function ( val, n ) {
                        return val.join( '<br>' ) + '<br>' + n
                    } )[ 0 ];
                } else {
                    var errs = _.reduce( rawErrors, function ( val, n ) {
                        return ( _.isString( val ) ? val : val.join( '<br>' ) ) + '<br>' + n
                    } );
                }

                swal.fire( {
                    html: errs,
                    icon: 'warning',
                } )

                return
            } else if ( ( err.response.status == 500 && err.response.data == 'Expired token' ) || ( err.response.status == 500 && err.response.data.message == 'Expired token' ) ) {

                swal.fire( {
                    title: 'Session timed out',
                    text: 'You have been idle for too long. For your security, login again!',
                    icon: 'info',
                } ).then( () => {
                    location.reload()
                } )
            } else if ( err.response.status == 404 ) {
                swal.fire( {
                    title: '404',
                    text: 'Resource Not Found',
                    icon: 'info',
                } )
            } else if ( err.response.status == 403 ) {
                swal.fire( {
                    title: 'Forbidden',
                    text: 'Action is forbidden to user',
                    icon: 'error',
                } )
            } else if ( err.response.status == 401 ) {
                swal.fire( {
                    title: 'Access Denied',
                    text: 'Access to requested resource is denied',
                    icon: 'error',
                } ).then( () => {
                    location.reload()
                } )
            } else if ( err.response.status == 429 ) {
                swal.fire( {
                    title: 'Spam Protection Activated!',
                    text: 'You have made too many attempts. Try again later.',
                    icon: 'error',
                } )
            } else {
                return Promise.reject( err )
            }
        } else if ( err.request ) {
            // console.log( err.request )

            if ( err.request.status == 422 ) {
                swal.fire( {
                    title: err.request.data.error,
                    html: err.request.data.message,
                    icon: 'warning',
                } )
            } else {
                return Promise.reject( err )
            }
        } else {
            // console.log( err )
            swal.fire( 'Request Error', `${err.message}`, 'error' )
        }
    }
)

let token = document.head.querySelector( 'meta[name="csrf-token"]' )
if ( token ) {
    window.axios.defaults.headers.common[ 'X-CSRF-TOKEN' ] = token.content
}
