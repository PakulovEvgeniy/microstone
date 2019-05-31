import Vue from 'vue'
import Router from 'vue-router'
import PageComp from './components/Page.vue';
import loginComp from './components/pages/login.vue';
import registerComp from './components/pages/register.vue';
import passwordLink from './components/pages/passwordlink.vue';
import passwordReset from './components/pages/passwordreset.vue';
import Category from './components/pages/category.vue';
import Home from './components/pages/home.vue';

Vue.use(Router);

function PageComponent(name) {
 return {
   render: h => h('h3', `Helo from the ${name} page`)
 };
}

export default new Router({
  mode: 'history',
  routes: [
    { path: '/', component: Home, name: 'home' },
    { path: '/category', component: Category, name: 'allCategory' },
    { path: '/category/:id', component: Category, name: 'category' },
    { path: '/about', component: PageComp, name: 'about' },
    { path: '/contact', component: PageComponent('Contact'), name: 'contact' },
    { path: '/account', component: PageComponent('Account'), name: 'account' },
    { path: '/login', component: loginComp, name: 'login' },
    { path: '/register', component: registerComp, name: 'register' },
    { path: '/password/reset', component: passwordLink, name: 'passwordLink' },
    { path: '/password/reset/:token', component: passwordReset, name: 'passwordReset' }
  ]
});