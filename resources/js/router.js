import Vue from 'vue'
import Router from 'vue-router'
import PageComp from './components/Page.vue';
import loginComp from './components/pages/login.vue';
import registerComp from './components/pages/register.vue';
import passwordLink from './components/pages/password-link.vue';
import passwordReset from './components/pages/password-reset.vue';
import Category from './components/pages/category.vue';
import CategoryF from './components/pages/category-filters.vue';
import Home from './components/pages/home.vue';
import Product from './components/pages/product.vue';
import Manufacturer from './components/pages/manufacturer.vue';
import Not_found from './components/pages/not-found.vue';
import Account from './components/pages/account.vue';
import emailVerify from './components/pages/email-verify.vue';

import auth from './middleware/auth.js';
import guest from './middleware/guest.js';
import notverify from './middleware/notverify.js';

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
    { path: '/category/:id', component: Category, name: 'category'},
    { path: '/category/:idF/filters', component: CategoryF, name: 'filtersCategory'},
    { path: '/product/:id', component: Product, name: 'product'},
    { path: '/manufacturer', component: Manufacturer, name: 'allManufacturer' },
    { path: '/manufacturer/:id', component: Manufacturer, name: 'manufacturer'},
    { path: '/about', component: PageComp, name: 'about' },
    { path: '/contact', component: PageComponent('Contact'), name: 'contact' },
    { path: '/account', component: Account, name: 'account',
      meta: {
          middleware: [
            auth
          ]
      }
    },
    { path: '/account/:id/:act', component: Account, name: 'account_id_act',
      meta: {
          middleware: [
            auth
          ]
      }
    },
    { path: '/account/:id', component: Account, name: 'account_id',
      meta: {
          middleware: [
            auth
          ],
          excludePath: [
            '/account/wishlist'
          ]
      }
    },
    { path: '/login', component: loginComp, name: 'login',
      meta: {
          middleware: [
            guest
          ]
      }
    },
    { path: '/register', component: registerComp, name: 'register',
      meta: {
          middleware: [
            guest
          ]
      }
    },
    { path: '/password/reset', component: passwordLink, name: 'passwordLink',
      meta: {
         middleware: [
          guest
        ]
      }
    },
    { path: '/password/reset/:token', component: passwordReset, name: 'passwordReset',
    meta: {
      middleware: [
          guest
        ]
      }
    },
    { path: '/email/verify', component: emailVerify, name: 'emailVerify',
      meta: {
        middleware: [
          auth,
          notverify
        ]
      }
    },
    { path: '/*', component: Not_found, name: 'not-found'}
  ]
});