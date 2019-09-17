import app from './app';
require ('./bootstrap.js');
require ('./vs-notify.js');

if (window.__INITIAL_STATE__) {
  app.$store.replaceState(window.__INITIAL_STATE__)
}
if (app.$notify) {
	window.$notify = app.$notify;
}

app.$mount('#app');