import 'font-awesome/css/font-awesome.min.css';
import Vue from 'vue';
import "regenerator-runtime/runtime";
import "ie-array-find-polyfill";
import "jspolyfill-array.prototype.findIndex";
import 'promise-polyfill/src/polyfill';
import './suggest.js';
//import '@babel/polyfill';
var keys = Object.keys;
Object.keys = function (obj) {
  if (typeof obj !== 'object') {
    return keys({})
  } else {
    return keys(obj);
  }
}

import '../less/app.less';
import app from './app';
require ('./bootstrap.js');
require ('./vs-notify.js');

if (window.__INITIAL_STATE__) {
  app.$store.replaceState(window.__INITIAL_STATE__)
}
if (app.$notify) {
	window.$notify = app.$notify;
}
if (app.$router) {
	window.$router = app.$router;
}

app.$mount('#app');