let fs = require('fs-extra')
let modules = fs.readdirSync('./main/app/Modules')
const mix = require('laravel-mix');
const {
	CleanWebpackPlugin
} = require('clean-webpack-plugin');

if (modules && modules.length > 0) {
	modules.forEach(module => {
		let path = `./main/app/Modules/${module}/webpack.mix.js`
		if (fs.existsSync(path)) {
			require(path)
		}
	})
}

mix.setPublicPath('./public_html')

mix.babelConfig({
	plugins: [
    '@babel/plugin-syntax-dynamic-import'
  ],
});

mix.webpackConfig({
	output: {
		filename: "[name].[chunkhash].js",
		chunkFilename: "[name].[chunkhash].js",
	},
	plugins: [
    new CleanWebpackPlugin({
			dry: false,
			cleanOnceBeforeBuildPatterns: ['js/*', 'css/*', '/img/*', 'fonts/*', 'robots.txt', 'mix-manifest.json',
				'./*.js']

		}),
  ]
});

mix.then(() => {
	const _ = require('lodash')
	// let manifestData = require( './public_html/mix-manifest' )
	let oldManifestData = JSON.parse(fs.readFileSync('./public_html/mix-manifest.json', 'utf-8'))

	let newManifestData = {};

	_.map(oldManifestData, (actualFilename, mixKeyName) => {

		if (_.startsWith(mixKeyName, '/css')) {
			/** Exclude CSS files from renaming for now till we start cache busting them */
			newManifestData[mixKeyName] = actualFilename;
			// newManifestData[mixKeyName] = actualFilename;
		} else {

			/**
			 * Remove the hash from the mix key name so that we can reference the files
			 * by their base name in our codes and mix will automatically replace that
			 * with a call to the hashed actual file name
			 */
			let newMixKeyName = _.split(mixKeyName, '.')
				.tap(o => {
					_.pullAt(o, 1);
				})
				.join('.')

			/** If the js extension has been stripped we add it back */
			newMixKeyName = _.endsWith(newMixKeyName, '.js') ? newMixKeyName : newMixKeyName + '.js'

			newManifestData[newMixKeyName] = actualFilename;
		}

	});

	let data = JSON.stringify(newManifestData, null, 2);
	fs.writeFileSync('./public_html/mix-manifest.json', data);
})

mix
	.options({
		fileLoaderDirs: {
			images: 'img'
		},
		postCss: [
			require('postcss-fixes')(),
		],
	})
	.extract();

mix.copyDirectory(__dirname + '/main/resources/site-assets/', 'public_html/basicsite');

if (!mix.inProduction()) {
	mix.sourceMaps();
}
