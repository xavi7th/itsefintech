const mix = require( 'laravel-mix' )
require( 'laravel-mix-merge-manifest' )

mix.webpackConfig( {
    resolve: {
        extensions: [ '.js', '.vue', '.json' ],
        alias: {
            '@normalAdmin-components': __dirname + '/Resources/js/components',
            '@normalAdmin-assets': __dirname + '/Resources'
        },
    },
} )

mix.js( __dirname + '/Resources/js/app.js', 'js/normalAdmin-app.js' )
mix.js( __dirname + '/Resources/js/auth.js', 'js/normalAdmin-auth-app.js' )

if ( mix.inProduction() ) {
    mix.version()
}
