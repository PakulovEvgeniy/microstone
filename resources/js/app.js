import App from './components/App.vue';
import Vue from 'vue';
import router from './router';
import {createStore} from './store';

var store = createStore();

export default new Vue({
    router,
    store,
    render: h => h(App)
});