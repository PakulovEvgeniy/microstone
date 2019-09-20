const path = require('path');
const VueLoaderPlugin = require('vue-loader/lib/plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

const mode = process.env.NODE_ENV;
const isDev = mode==='development';
const isProd = !isDev;

module.exports = {
  watch: isDev,
  mode: mode,
  entry: {
  	'entry-server': './resources/js/entry-server.js',
  	'entry-client': './resources/js/entry-client.js'
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
        include: /server/,
        use: [
          {
            loader: MiniCssExtractPlugin.loader
          },
          {
             loader: 'css-loader'
          },
        ]
      },
      {
        test: /\.less$/,
        use: [
          {
            loader: MiniCssExtractPlugin.loader
          },
          {
             loader: 'css-loader'
          },
          {
            loader: 'less-loader',
          }
        ]
      }
  	]
  },
  plugins: [
    new VueLoaderPlugin(),
    new MiniCssExtractPlugin({
          filename: '[name].css'
        })
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