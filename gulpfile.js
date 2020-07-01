const gulp = require('gulp');
/*const autoprefixer = require('gulp-autoprefixer');
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
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const through = require('through2');
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');*/
const smartgrid = require('smart-grid');


/*console.log(process.argv.indexOf('--dev'));

const isDev = process.argv.indexOf('--dev') !== -1;
const isProd = !isDev;
const isSync = process.argv.indexOf('--sync') !== -1;

function clear() {
	return del('./public/css/*');
}

function server(cb) {
	gulp.src('./resources/js/entry-server.js')
		//.pipe(gulpif(isDev,sourcemaps.init({loadMaps: true})))
		.pipe(webpack({
			mode: isProd ? 'production' : 'development',
			watch: true,
			devtool: 'source-map',
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
				          {
						  	loader: MiniCssExtractPlugin.loader,
						  	options: {
                                sourceMap: true
                            }
						  },
				          {
                            loader: 'css-loader',
                            options: {
                                sourceMap: true
                            }
                          }
				        ]
				    },
				    {
					  test: /\.less$/,
					  use: [
					    {
						  	loader: MiniCssExtractPlugin.loader,
						  	options: {
                                sourceMap: true
                            }
						},
					    {
                            loader: 'css-loader',
                            options: {
                                sourceMap: true
                            }
                        },
                        {
                            loader: 'less-loader',
                            options: {
                                sourceMap: true
                            }
                        }
					  ]
					}
				]
			},
  			plugins: [
				new VueLoaderPlugin(),
				new MiniCssExtractPlugin({
					filename: '../../resources/less/vue-styles.less',
					ignoreOrder: false
				}),
				new OptimizeCssAssetsPlugin({
       				cssProcessorOptions: {
				        map: {
				           //inline: false,
				           //annotation: true
				        }
				    }
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
		}))*/
		/*.pipe(gulpif(isDev,sourcemaps.init({loadMaps: true})))
		.pipe(gulpif(isDev,through.obj(function (file, enc, cb) {
			const isSourceMap  = /\.map$/.test(file.path);
			if (!isSourceMap) {
				this.push(file);
			}
			cb()
		})))
		.pipe(gulpif(isDev, sourcemaps.write()))*/
/*		.pipe(gulp.dest('./public/js'));
	cb();
}

function styles() {
	return gulp.src('./resources/less/app.less')
		.pipe(gulpif(isDev,sourcemaps.init({loadMaps: true})))
		.pipe(less({}))
		.pipe(autoprefixer({
			overrideBrowserslist: ['> 0.1%'],
            cascade: true
        }))
        .pipe(gulpif(isProd,cleanCSS({level: 2})))
        .pipe(gulpif(isProd,gcmq()))
        .pipe(gulpif(isDev, sourcemaps.write()))
        .pipe(gulp.dest('./public/css'))
		.pipe(gulpif(isSync, browserSync.stream()));
}

let build = gulp.series(clear,server
	//gulp.parallel(styles)
);
*/
function watch() {
	if (isSync) {
		browserSync.init({
		  open: 'external',
	      proxy: 'http://microstone:81',
	      port: 3000
		});
	}
	gulp.watch('./resources/less/**/*.less', styles);
	gulp.watch(['./public/js/entry-client.js', './public/js/entry-server.js', './app/**/*.*']).on('change', browserSync.reload);
}

function grid(done){
	let settings = {
		columns: 24,
    	offset: "10px",
    	//mobileFirst: true,
    	container: {
	        maxWidth: "1200px",
	        fields: "30px"
	    },
    	breakPoints: {
    		lg: {
    			width: "1200px"
    		},
    		md: {
	            width: "992px",
	            fields: "15px"
	        },
	        sm: {
	            width: "768px",
	            fields: "10px"
	        },
	        xs: {
	        	width: "576px"
	        }
    	}
	};

	smartgrid('./resources/less', settings);
	done();
}

//gulp.task('build', build);
//gulp.task('watch', gulp.parallel(build, watch));
gulp.task('grid', grid);

