const mix = require( 'laravel-mix' )
require( 'laravel-mix-merge-manifest' )

mix.webpackConfig( {
    resolve: {
        extensions: [ '.js', '.vue', '.json' ],
        alias: {
            '@cardAdmin-components': __dirname + '/Resources/js/components',
            '@cardAdmin-assets': __dirname + '/Resources'
        },
    },
} )

mix.js( __dirname + '/Resources/js/app.js', 'js/cardAdmin-app.js' )
mix.js( __dirname + '/Resources/js/auth.js', 'js/cardAdmin-auth-app.js' )


if ( mix.inProduction() ) {
    mix.version()
}
