const mix = require('laravel-mix');
mix.setPublicPath( './public_html' )


let fs = require( 'fs-extra' )

let modules = fs.readdirSync( './main/app/Modules' ) // Make sure the path of your modules are correct

if ( modules && modules.length > 0 ) {
	modules.forEach( module => {
		let path = `./main/app/Modules/${module}/webpack.mix.js`
		if ( fs.existsSync( path ) ) {
			require( path )
		}
	} )
}


/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
mix
	// .sourceMaps()
	.options( {
		fileLoaderDirs: {
			images: 'img',
			// fonts: 'web-fonts'
		},
		postCss: [
			require( 'postcss-fixes' )(), // add fallbacks for rem units and other fixes
		],
	} )
	.extract( [ 'vue', 'sweetalert2', 'axios', 'lodash', 'vue-plugin-load-script', 'vue-router' ] )
	.version()


if ( !mix.inProduction() ) {
	mix.sourceMaps();
}



// mix.webpackConfig( {
// 	devtool: 'source-map',
// } )

// mix.browserSync( {
// 	injectChanges: true,
// 	ghostMode: {
// 		clicks: false,
// 		forms: false,
// 		scroll: false,
// 	},
// 	proxy: {
// 		target: 'localhost:8000',
// 		reqHeaders: function () {
// 			return {
// 				host: 'localhost:3000',
// 			}
// 		},
// 	},
// } )