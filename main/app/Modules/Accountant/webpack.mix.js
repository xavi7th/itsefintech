const mix = require( 'laravel-mix' )
require( 'laravel-mix-merge-manifest' )

mix.webpackConfig( {
    resolve: {
        extensions: [ '.js', '.vue', '.json' ],
        alias: {
            '@accountant-components': __dirname + '/Resources/js/components',
            '@accountant-assets': __dirname + '/Resources'
        },
    },
} )

mix.js( __dirname + '/Resources/js/app.js', 'js/accountant-app.js' )
mix.js( __dirname + '/Resources/js/auth.js', 'js/accountant-auth-app.js' )

if ( mix.inProduction() ) {
    mix.version()
}
