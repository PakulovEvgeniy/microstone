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
var MiniCssExtractPlugin = require('mini-css-extract-plugin');



console.log(process.argv.indexOf('--dev'));

const isDev = process.argv.indexOf('--dev') !== -1;
const isProd = !isDev;
const isSync = process.argv.indexOf('--sync') !== -1;

function clear() {
	return del('./public/css/*');
}

function server(cb) {
	gulp.src('./resources/js/entry-server.js')
		.pipe(webpack({
			mode: isProd ? 'production' : 'development',
			watch: true,
			entry: {
				'entry-server': [
				  './resources/js/entry-server.js'
				],
				'entry-client': [
				  './resources/js/entry-client.js'
				]
			},
			output: {
    			filename: '[name].js'
  			},
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
						  MiniCssExtractPlugin.loader,
				          'css-loader'
				        ]
				    },
				    {
					  test: /\.less$/,
					  use: [
					    MiniCssExtractPlugin.loader,
					    'css-loader',
					    'less-loader'
					  ]
					}
				]
			},
  			plugins: [
				new VueLoaderPlugin(),
				new MiniCssExtractPlugin({
					filename: '../../resources/less/vue-styles.less',
					ignoreOrder: false
				})
			],
			stats: {
				"hash": false,
				"version": false,
				"timings": false,
				"children": false,
				"errorDetails": false,
				"entrypoints": false,
				"performance": false,
				"chunks": false,
				"modules": false,
				"reasons": false,
				"source": false,
				"publicPath": false,
				"builtAt": false
			},
			resolve: {
				extensions: [
				  "*",
				  ".wasm",
				  ".mjs",
				  ".js",
				  ".jsx",
				  ".json",
				  ".vue"
				],
				alias: {
				  "vue$": "vue/dist/vue.common.js"
				}
			}
			  
		}))
		.pipe(gulp.dest('./public/js'));
	cb();
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

let build = gulp.series(clear,server
	//gulp.parallel(styles)
);

function watch() {
	if (isSync) {
		browserSync.init({
		  open: 'external',
	      host: 'microstone',
	      proxy: 'http://microstone',
	      port: 3000
		});
	}
	gulp.watch('./resources/less/**/*.less', styles);
	gulp.watch(['./public/js/entry-client.js', './public/js/entry-server.js', './app/**/*.*']).on('change', browserSync.reload);
}

gulp.task('build', build);
gulp.task('watch', gulp.parallel(build, watch));

