const gulp = require('gulp');
const autoprefixer = require('gulp-autoprefixer');
const del = require('del');
const browserSync = require('browser-sync').create();
const concat = require('gulp-concat');
const cleanCSS = require('gulp-clean-css');
const sourcemaps = require('gulp-sourcemaps');
const gulpif = require('gulp-if');
const gcmq = require('gulp-group-css-media-queries');
const less = require('gulp-less');
const webpack = require('webpack-stream');
const VueLoaderPlugin = require('vue-loader/lib/plugin');
const rename = require("gulp-rename");
const VueSSRServerPlugin = require('vue-server-renderer/server-plugin')

let ManifestPlugin = require('laravel-mix/src/webpackPlugins/ManifestPlugin');
let MockEntryPlugin = require('laravel-mix/src/webpackPlugins/MockEntryPlugin');


console.log(process.argv.indexOf('--dev'));

const isDev = process.argv.indexOf('--dev') !== -1;
const isProd = !isDev;
const isSync = process.argv.indexOf('--sync') !== -1;

function clear() {
	return del('./public/css/*');
}

function server() {
	return gulp.src('./resources/js/entry-server.js')
		.pipe(webpack({
			mode: isProd ? 'production' : 'development',
			target: 'web',
			output: {
    			libraryTarget: 'commonjs2',
    			filename: 'entry-server.js'
  			},
  			externals: Object.keys(require('./package.json').dependencies),
			module: {
				rules: [
					{
						test: /\.vue$/,
        				loader: 'vue-loader'
        			},
        			{
        				test: /\.js$/,
        				loader: 'babel-loader'
      				},
      				{
				        test: /\.css$/,
				        use: [
				          'vue-style-loader',
				          'css-loader'
				        ]
				    },
				    {
					  test: /\.less$/,
					  use: [
					    'vue-style-loader',
					    'css-loader',
					    'less-loader'
					  ]
					}
				]
			},
  			plugins: [
        		new VueLoaderPlugin(),
  			]
		}))
		.pipe(gulp.dest('./public/js'))
}

function styles() {
	return gulp.src('./resources/less/app.less')
		.pipe(gulpif(isDev,sourcemaps.init()))
		.pipe(less({}))
		.pipe(autoprefixer({
			overrideBrowserslist: ['> 0.1%'],
            cascade: true
        }))
        .pipe(gulpif(isProd,cleanCSS({level: 2})))
        .pipe(gcmq())
        .pipe(gulpif(isDev, sourcemaps.write()))
        .pipe(gulp.dest('./public/css'))
		.pipe(gulpif(isSync, browserSync.stream()));
}

let build = gulp.series(clear,
	gulp.parallel(styles, server)
);

function watch() {
	if (isSync) {
		browserSync.init({
		  open: 'external',
	      host: 'microstone',
	      proxy: 'http://microstone:81',
	      port: 3000
		});
	}
	gulp.watch('./resources/less/**/*.less', styles);
}

gulp.task('build', build);
gulp.task('watch', gulp.series(build, watch));

