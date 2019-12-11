const mix = require( 'laravel-mix' )
require( 'laravel-mix-merge-manifest' )

mix.webpackConfig( {
    resolve: {
        extensions: [ '.js', '.vue', '.json' ],
        alias: {
            '@dispatchAdmin-components': __dirname + '/Resources/js/components',
            '@dispatchAdmin-assets': __dirname + '/Resources'
        },
    },
} )

mix.js( __dirname + '/Resources/js/app.js', 'js/dispatchAdmin-app.js' )
mix.js( __dirname + '/Resources/js/auth.js', 'js/dispatchAdmin-auth-app.js' )


if ( mix.inProduction() ) {
    mix.version()
}
