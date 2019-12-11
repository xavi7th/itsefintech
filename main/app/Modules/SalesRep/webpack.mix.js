const mix = require( 'laravel-mix' )
require( 'laravel-mix-merge-manifest' )

mix.webpackConfig( {
    resolve: {
        extensions: [ '.js', '.vue', '.json' ],
        alias: {
            '@salesRep-components': __dirname + '/Resources/js/components',
            '@salesRep-assets': __dirname + '/Resources'
        },
    },
} )

mix.js( __dirname + '/Resources/js/app.js', 'js/salesRep-app.js' )
mix.js( __dirname + '/Resources/js/auth.js', 'js/salesRep-auth-app.js' )


if ( mix.inProduction() ) {
    mix.version()
}
