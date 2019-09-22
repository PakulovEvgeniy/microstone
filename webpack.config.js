const path = require('path');
const VueLoaderPlugin = require('vue-loader/lib/plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');

const mode = process.env.NODE_ENV;
const isDev = mode==='development';
const isProd = !isDev;


let conf = {
  watch: isDev,
  mode: mode,
  entry: {
  	'entry-server': './resources/js/entry-server.js'
  },
  output: {
    filename: '[name].js',
    path: path.resolve(__dirname, 'public/js')
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
        loader: 'null-loader'
      },
      {
        test: /\.less$/,
        loader: 'null-loader'
      }
  	]
  },
  plugins: [
    new VueLoaderPlugin()
  ],
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
  },
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
      }
};

let conf2 = {
  watch: isDev,
  mode: mode,
  devtool: 'inline-source-map',
  entry: {
  	'entry-client': ['./resources/js/entry-client.js']
  },
  output: {
    filename: '[name].js',
    path: path.resolve(__dirname, 'public/js'),
  },
  module: {
  	rules: [
      {
        test: /\.vue$/,
        loader: 'vue-loader'
      },
      {
      test: /\.(ttf|eot|svg|gif|png|woff|woff2|jpg|jpeg)$/,
            use: [{
                loader: 'file-loader'
            }]
      },
  		{
      	test: /\.js$/,
        loader: 'babel-loader',
        options: {
          presets: [
            '@babel/preset-env'
          ],
          plugins: [
            "@babel/plugin-transform-object-assign"
          ]
        }
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
          },
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
          filename: '[name].css'
        }),
    /*new BrowserSyncPlugin({
          // browse to http://localhost:3000/ during development,
          // ./public directory is being served
          open: 'external',
          proxy: 'http://microstone',
          port: 3000 
        })*/
  ],
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
  },
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
      }
};

module.exports = [
  conf,
  conf2
];