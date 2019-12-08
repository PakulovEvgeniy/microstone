import Vue from 'vue';
import Vuex from 'vuex';
import mSystem from './store-modules/system.js';
import mUser from './store-modules/user.js';
import mBrands from './store-modules/brands.js';
import mAccount from './store-modules/user-account.js';
import mCatalog from './store-modules/catalog.js';
import mWishlist from './store-modules/wishlist.js';
import mCompare from './store-modules/compare.js';
import mCart from './store-modules/cart.js';
import mCatalogFilters from './store-modules/catalog-filters.js';

Vue.use(Vuex);


export function createStore () {
  return new Vuex.Store({
    modules: {
      mSystem,
      mUser,
      mBrands,
      mAccount,
      mCatalog,
      mCatalogFilters,
      mWishlist,
      mCompare,
      mCart
    },
    state: {
      auth: false,
      isVerify: false,
      csrf: '',
      mounted: false,
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
        items: [
        ],
        products: [
        ]
      },
      wishlist: {
        items: [
        ],
        products:[
        ],
        curGroup: null,
        curName: '',
        groups: []
      },
      waitlist: {
        items: [
        ]
      },
      compare: {
        items: [
        ],
        products:[
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
      setMounted(state, payload) {
        state.mounted = payload;
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
      hasNotifies(state) {
        return true
      },
      waitlist(state) {
        return state.waitlist;
      },
      mounted(state) {
        return state.mounted;
      }
    }
  })
}