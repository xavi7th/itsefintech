const mix = require( 'laravel-mix' )
require( 'laravel-mix-merge-manifest' )

mix.webpackConfig( {
    resolve: {
        extensions: [ '.js', '.vue', '.json' ],
        alias: {
            '@accountOfficer-components': __dirname + '/Resources/js/components',
            '@accountOfficer-assets': __dirname + '/Resources'
        },
    },
} )

mix.js( __dirname + '/Resources/js/app.js', 'js/accountOfficer-app.js' )
mix.js( __dirname + '/Resources/js/auth.js', 'js/accountOfficer-auth-app.js' )

if ( mix.inProduction() ) {
    mix.version()
}
