import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export function createStore () {
  return new Vuex.Store({
    state: {
      name: '',
      auth: false,
      csrf: '',
      userEmail: '',
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
      catalog: {
        date: '',
        items: []
      },
      banners: {
        date: '',
        items: []
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
      product: {}
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
      async getBanners({commit, state}, data) {
        if (state.banners.items.length) {
          if (!state.banners.date) {
            state.banners.date = new Date();
            return;
          }
          let nDat = new Date();
          let dif = (nDat.getTime() - state.catalog.date.getTime())/1000;
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
      setFilters (state, payload) {
        state.filterItems = payload.filters;
        state.filterItemsDef = payload.filtersDef;
      },
      setCatalog(state, payload) {
        state.catalog.date = new Date();
        state.catalog.items = payload;
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
      auth (state) {
        return state.auth;
      },
      userEmail (state) {
        return state.userEmail;
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
    }
  })
}