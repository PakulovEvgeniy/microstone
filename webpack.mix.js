const mix = require('laravel-mix');

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
require('laravel-mix-polyfill');

mix.js('resources/js/entry-client.js', 'public/js')
	.polyfill({
		targets: {"firefox": "50", "ie": 11}
	})

mix.js('resources/js/entry-server.js', 'public/js');

mix.options({
    extractVueStyles: true
});

