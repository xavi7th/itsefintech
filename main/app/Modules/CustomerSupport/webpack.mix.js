const mix = require( 'laravel-mix' )
require( 'laravel-mix-merge-manifest' )

mix.webpackConfig( {
    resolve: {
        extensions: [ '.js', '.vue', '.json' ],
        alias: {
            '@customerSupport-components': __dirname + '/Resources/js/components',
            '@customerSupport-assets': __dirname + '/Resources'
        },
    },
} )

mix.js( __dirname + '/Resources/js/app.js', 'js/customerSupport-app.js' )
mix.js( __dirname + '/Resources/js/auth.js', 'js/customerSupport-auth-app.js' )


if ( mix.inProduction() ) {
    mix.version()
}
