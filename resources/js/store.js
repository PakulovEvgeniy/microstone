import Vue from 'vue';
import Vuex from 'vuex';
import mSystem from './store-modules/system.js';
import mUser from './store-modules/user.js';
import mBrands from './store-modules/brands.js';
import mAccount from './store-modules/user-account.js';
import mCatalog from './store-modules/catalog.js';
import mCatalogFilters from './store-modules/catalog-filters.js';

Vue.use(Vuex);


export function createStore () {
  return new Vuex.Store({
    modules: {
      system: mSystem,
      user: mUser,
      brands: mBrands,
      account: mAccount,
      catalog: mCatalog,
      catalogFilters: mCatalogFilters
    },
    state: {
      auth: false,
      isVerify: false,
      csrf: '',
      userEmail: '',
      userPersonal: {
        date: undefined,
        data: {}
      },
      userContragents: {
        date: undefined,
        items: []
      },
      userAddresses: [],
      settings: {},
      scrolled: 0,
      screenWidth: 0,
      screenHeight: 0,
      nonVisibleMain: false,
      nonVisibleAside: false,
      visBacdrop:false,
      idTimeStartBack: null,
      idTimeStopBack: null,
      resetEmail: {},
      pageManuf: 1,
      bodyBlocked: false,
      catalog: {
        date: '',
        items: []
      },
      brands: {
        date: '',
        items: []
      },
      curBrand: {},
      banners: {
        date: '',
        items: []
      },
      popularProducts: {
        category: [],
        product: []
      },
      categoryFilters: {},
      groupValues: [],
      topFilters: {
        stock:  {
          'name' : 'stock',
          'caption' : 'Наличие:',
          'items' : [
            {
              'id' : 1,
              'name' : 'В наличии и под заказ'
            },
            {
              'id' : 2,
              'name' : 'В наличии'
            },
            {
              'id' : 3,
              'name' : 'Под заказ'
            }
          ]
        }
      },
      productsOfCategoryPage: {
        totalQty: 0,
        items: []
      },
      filterItems: [],
      filterItemsDef: [],
      product: {},
      activeWait: {
        'block': false,
        'wait': false
      },
      cart: {
        cart_items: [
          {id: 0}
        ]
      },
      wishlist: {
        items: [
          {id: 0}
        ]
      },
      waitlist: {
        items: [
          {id: 0}
        ]
      }
    },
    mutations: {
      setBanners(state, payload) {
        state.banners.date = new Date();
        state.banners.items = payload;
      },
      setProduct(state, payload) {
        state.product = payload;
      },
      setPopularProducts(state, payload) {
        state.popularProducts.category = payload.category;
        state.popularProducts.product = payload.product;
      }
    },
    getters: {
     
      banners (state) {
        return state.banners.items;
      },
      product(state) {
        return state.product;
      },
      popularProducts(state) {
        return state.popularProducts
      },
      cartQty(state) {
        return state.cart.cart_items.length;
      },
      hasNotifies(state) {
        return true
      },
      wishlist(state) {
        return state.wishlist;
      },
      waitlist(state) {
        return state.waitlist;
      }
    }
  })
}