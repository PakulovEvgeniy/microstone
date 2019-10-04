import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);


export function createStore () {
  let ta=undefined;
  return new Vuex.Store({
    state: {
      name: '',
      auth: false,
      isVerify: false,
      csrf: '',
      userEmail: '',
      userPersonal: {
        date: undefined,
        data: {}
      },
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
    actions: {
      setAuth ({ commit }, data) {
        let dat = data.dat;
        let vm = data.vm;
        if (dat.status && dat.status == 'success') {
            if (dat.email) {
                commit('setAuth', true);
                commit('setEmail', dat.email);  
            }
            if (dat.isVerify) {
              commit('setVerify', dat.isVerify);
            }
        }
        if (dat.csrf) {
            commit('setCsrf', dat.csrf);
            axios.defaults.headers.common['X-CSRF-TOKEN'] = dat.csrf;
        }
        if (dat.redirectTo) {
            vm.$router.push(dat.redirectTo);
        }
        if (dat.error) {
          vm.$notify("alert", dat.error, "error");
        }
        if (dat.message) {
          vm.$notify("alert", dat.message, "success");
        }
      },
      showWait({commit}, data) {
        commit('setActiveBlock', true);
        ta = setTimeout(() => {
          commit('setActiveWait', true);
        }, 200);
      },
      closeWait({commit}, data) {
        clearTimeout(ta);
        commit('setActiveWait', {'wait': false, 'block': false});
      },
      showError({commit}, data) {
        let e = data;
        if (e.response && e.response.data && e.response.data.errors) {
          let err = e.response.data.errors;
          if (err) {
              for(let el in err) {
                  $notify("alert", err[el][0], "error");
                  break;
              }
          }
        } else {
             $notify("alert", e.message, "error");
        }
        clearTimeout(ta);
        commit('setActiveWait', {'wait': false, 'block': false});
      },
      async getCatalog({commit, state}, data) {
        if (state.catalog.items.length) {
          if (!state.catalog.date) {
            state.catalog.date = new Date();
            return;
          }
          let nDat = new Date();
          let dif = (nDat.getTime() - state.catalog.date.getTime())/1000;
          if (dif<=3600) {
            return;
          }
        }
        let res = await axios.get('/api/products/category');
        let dat = res.data;

        if (dat && dat.status == 'OK') {
          commit('setCatalog', dat.data);
        }
      },
      async getBrands({commit, state}, data) {
        if (state.brands.items.length) {
          if (!state.brands.date) {
            state.brands.date = new Date();
            return;
          }
          let nDat = new Date();
          let dif = (nDat.getTime() - state.brands.date.getTime())/1000;
          if (dif<=3600) {
            return;
          }
        }
        let res = await axios.get('/api/products/brands');
        let dat = res.data;

        if (dat && dat.status == 'OK') {
          commit('setBrands', dat.data);
        }
      },
      async getBanners({commit, state}, data) {
        if (state.banners.items.length) {
          if (!state.banners.date) {
            state.banners.date = new Date();
            return;
          }
          let nDat = new Date();
          let dif = (nDat.getTime() - state.banners.date.getTime())/1000;
          if (dif<=3600) {
            return;
          }
        }
        let res = await axios.get('/api/products/banners');
        let dat = res.data;

        if (dat && dat.status == 'OK') {
          commit('setBanners', dat.data);
        }
      },
      async getPopularProducts({commit, state}, data) {
        let res = await axios.get('/api/products/popular');
        let dat = res.data;
        if (dat && dat.status == 'OK') {
          commit('setPopularProducts', dat.data);
        }
      },
      async getCurBrand({commit, state}, data) {
        let res = await axios.get('/api/products/curbrand?chpu='+data);
        let dat = res.data;
        if (dat && dat.status == 'OK') {
          commit('setCurBrand', dat.data);
        }
      },
      async getOrders({commit, state}, data) {
        let res = await axios.get('/api/products/orders?chpu='+data);
        let dat = res.data;
        if (dat && dat.status == 'OK') {
          commit('setOrders', dat.data);
        }
      },
      async getProduct({commit, state}, data) {
        let res = await axios.get('/api/products/product?chpu='+data);
        let dat = res.data;
        if (dat && dat.status == 'OK') {
          commit('setProduct', dat.data);
        }
      },
      async getFilters({commit, state}, data) {
        let res = await axios.get('/api/products/filters?chpu='+data);
        let dat = res.data;
        if (dat && dat.status == 'OK') {
          commit('setFilters', dat.data);
        }
      },
      async getProductPage({commit, state}, data) {
        let res = await axios.get('/api/products/productpage', {params: data});
        let dat = res.data;
        if (dat && dat.status == 'OK') {
          commit('setProductsOfCategoryPage', dat.data);
        }
      },
      async getGroups({commit, state}, data) {
        let res = await axios.get('/api/products/groups?chpu='+data);
        let dat = res.data;
        if (dat && dat.status == 'OK') {
          commit('setGroups', dat.data);
        }
      },
      async getUserPersonal({commit, state}, data) {
        if (state.userPersonal.date || (state.userPersonal.date === '')) {
          if (state.userPersonal.date === '') {
            state.userPersonal.date = new Date();
            return;
          }
          let nDat = new Date();
          let dif = (nDat.getTime() - state.userPersonal.date.getTime())/1000;
          if (dif<=3600) {
            return;
          }
        }
        let res = await axios.get('/account/personal');
        let dat = res.data;
        if (dat && dat.status == 'OK') {
          commit('setUserPersonal', dat.data);
        }
      }
    },
    mutations: {
      setName (state, payload) {
        state.name = payload;
      },
      setAuth (state, payload) {
        state.auth = payload;
      },
      setCsrf (state, payload) {
        state.csrf = payload;
      },
      setEmail (state, payload) {
        state.userEmail = payload;
      },
      setVerify (state, payload) {
        state.isVerify = payload;
      },
      setSettings (state, payload) {
        state.settings = payload;
      },
      setScrolled (state, payload) {
        state.scrolled = payload;
      },
      setScreenWidth (state, payload) {
        state.screenWidth = payload;
      },
      setScreenHeight (state, payload) {
        state.screenHeight = payload;
      },
      setVisBacdrop (state, payload) {
        state.visBacdrop = payload;
      },
      setIdTimeStartBack (state, payload) {
        state.idTimeStartBack = payload;
      },
      setIdTimeStopBack (state, payload) {
        state.idTimeStopBack = payload;
      },
      setNonVisibleMain (state, payload) {
        state.nonVisibleMain = payload;
      },
      setNonVisibleAside (state, payload) {
        state.nonVisibleAside = payload;
      },
      setPageManuf (state, payload) {
        state.pageManuf = payload;
      },
      setCurBrand (state, payload) {
        state.curBrand = payload;
      },
      setFilters (state, payload) {
        state.filterItems = payload.filters;
        state.filterItemsDef = payload.filtersDef;
      },
      setCatalog(state, payload) {
        state.catalog.date = new Date();
        state.catalog.items = payload;
      },
      setBrands(state, payload) {
        state.brands.date = new Date();
        state.brands.items = payload;
      },
      setUserPersonal(state, payload) {
        state.userPersonal.date = new Date();
        state.userPersonal.data = payload;
      },
      setUserPersonalObj(state, payload) {
        for(let key in payload) {
          state.userPersonal.data[key] = payload[key];
        }
      },
      setBanners(state, payload) {
        state.banners.date = new Date();
        state.banners.items = payload;
      },
      setCategoryFilters(state, payload) {
        if (state.categoryFilters[payload.name] === undefined) {
          Vue.set(state.categoryFilters, payload.name, payload.value);
        } else {
          state.categoryFilters[payload.name] = payload.value
        }
        if (payload.name == 'stock' && state.categoryFilters['page'] !== undefined) {
          state.categoryFilters['page'] = 1;
        }
      },
      setCategoryFiltersAll(state, payload) {
        let ob = {};
        let pat = /f\[(\d+)\]/;
        state.filterItems.forEach((el) => {
          if (el.filter_type == 'Число') {
            el.grp_data.minValue = '';
            el.grp_data.maxValue = '';
          } else {
            el.grp_data.fChecked = [];
          }
        });
        
        for (let key in payload) {
          ob[key] = payload[key];
          let mat = key.match(pat);
          if (mat) {
            let el = state.filterItems.find((it) => {
              return it.id_1s == mat[1];
            });
            if (el) {
              if (el.filter_type=='Число') {
                let ar = payload[key].split('-');
                if (ar.length == 2) {
                  el.grp_data.minValue = ar[0];
                  el.grp_data.maxValue = ar[1];
                }
              } else {
                let ar = payload[key].split('-');
                ar.forEach((it) => {
                  el.grp_data.fChecked.push(it);
                })
              }
            }
          }
        }
        state.categoryFilters = ob;
      },
      setOrders(state, payload) {
        state.topFilters['order'] =  payload;
        if (state.categoryFilters['order']) {
          let it = state.topFilters['order'].items.find((el) => {
            return el.id == state.categoryFilters['order'];
          });
          if (!it) {
            state.categoryFilters['order'] = state.topFilters['order'].items[0].id;
          }
        }
      },
      setGroups(state, payload) {
        state.topFilters['group'] =  payload;
        if (state.categoryFilters['group']) {
          let it = state.topFilters['group'].items.find((el) => {
            return el.id == state.categoryFilters['group'];
          });
          if (!it) {
            state.categoryFilters['group'] = state.topFilters['group'].items[0].id;
          }
        }
      },
      setProductsOfCategoryPage(state, payload) {
        state.productsOfCategoryPage = payload;
      },
      setProduct(state, payload) {
        state.product = payload;
      },
      setPopularProducts(state, payload) {
        state.popularProducts.category = payload.category;
        state.popularProducts.product = payload.product;
      },
      setActiveWait(state, payload) {
        if (typeof(payload) == 'boolean') {
          state.activeWait.wait = payload;
        } else {
          state.activeWait.wait = payload.wait;
          state.activeWait.block = payload.block;
        }
      },
      setActiveBlock(state, payload) {
        state.activeWait.block = payload
      },
      setBodyBlocked(state, payload) {
        state.bodyBlocked = payload;
      }
    },
    getters: {
      csrf(state) {
        return state.csrf;
      },
      settings (state) {
        return state.settings;
      },
      visBacdrop (state) {
        return state.visBacdrop;
      },
      scrolled (state) {
        return state.scrolled;
      },
      idTimeStartBack (state) {
        return state.idTimeStartBack;
      },
      idTimeStopBack (state) {
        return state.idTimeStopBack;
      },
      getCatalog (state) {
        return state.catalog;
      },
      banners (state) {
        return state.banners.items;
      },
      brands (state) {
        return state.brands.items;
      },
      auth (state) {
        return state.auth;
      },
      isVerify (state) {
        return state.isVerify;
      },
      userEmail (state) {
        return state.userEmail;
      },
      userPersonal(state) {
        return state.userPersonal.data;
      },
      resetEmail (state) {
        return state.resetEmail;
      },
      nonVisibleMain (state) {
        return state.nonVisibleMain;
      },
      nonVisibleAside (state) {
        return state.nonVisibleAside;
      },
      isBottomMenuFixed (state) {
        return state.scrolled>40;
      },
      getScreenState(state) {
        if (state.screenWidth==0 || state.screenWidth>1199) {
          return 3
        } else if (state.screenWidth<992) {
          return 1
        } else {
          return 2
        }
      },
      topFilters(state) {
        return state.topFilters
      },
      filterItems(state) {
        return state.filterItems;
      },
      filterItemsDef(state) {
        return state.filterItemsDef;
      },
      screenWidth(state) {
        return state.screenWidth;
      },
      screenHeight(state) {
        return state.screenHeight;
      },
      categoryFilters(state) {
        return state.categoryFilters;
      },
      totalQty(state) {
        return state.productsOfCategoryPage.totalQty;
      },
      productsOfCategoryPage(state) {
        return state.productsOfCategoryPage.items
      },
      product(state) {
        return state.product;
      },
      popularProducts(state) {
        return state.popularProducts
      },
      pageManuf(state) {
        return state.pageManuf
      },
      curBrand(state) {
        return state.curBrand;
      },
      activeWait(state) {
        return state.activeWait;
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
      },
      bodyBlocked(state) {
        return state.bodyBlocked;
      }
    }
  })
}