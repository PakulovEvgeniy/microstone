import Vue from 'vue'
import Router from 'vue-router'
import PageComp from './components/Page.vue';

Vue.use(Router);

function PageComponent(name) {
 return {
   render: h => h('h3', `Helo from the ${name} page`)
 };
}

export default new Router({
  mode: 'history',
  base: '/microstone/public/',
  routes: [
    { path: '/', component: PageComponent('Home'), name: 'home' },
    { path: '/about', component: PageComp, name: 'about' },
    { path: '/contact', component: PageComponent('Contact'), name: 'contact' }
  ]
});