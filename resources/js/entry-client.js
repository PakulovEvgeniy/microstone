import app from './app'

if (window.__INITIAL_STATE__) {
  app.$store.replaceState(window.__INITIAL_STATE__)
}

app.$mount('#app');