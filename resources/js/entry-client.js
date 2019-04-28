import app from './app'
require ('./bootstrap.js');

if (window.__INITIAL_STATE__) {
  app.$store.replaceState(window.__INITIAL_STATE__)
}

app.$mount('#app');