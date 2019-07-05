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
      productsOfCategory: [
      ],
      filterItems: []
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
      async getOrders({commit, state}, data) {
        let res = await axios.get('/api/products/orders?chpu='+data);
        let dat = res.data;
        if (dat && dat.status == 'OK') {
          commit('setOrders', dat.data);
        }
      },
      async getGroups({commit, state}, data) {
        let res = await axios.get('/api/products/groups?chpu='+data);
        let dat = res.data;
        if (dat && dat.status == 'OK') {
          commit('setGroups', dat.data);
        }
      },
      async getProductsCategory({commit, state}, data) {
        let res = await axios.get('/api/products/products_cat?chpu='+data);
        let dat = res.data;
        if (dat && dat.status == 'OK') {
          commit('setProductsCategory', dat.data);
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
      setCatalog(state, payload) {
        state.catalog.date = new Date();
        state.catalog.items = payload;
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
        for (let key in payload) {
          ob[key] = payload[key];
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
      setProductsCategory(state, payload) {
        state.productsOfCategory = payload;
        if (state.categoryFilters['page']) {
          state.categoryFilters['page'] = 1;
        } 
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
      screenWidth(state) {
        return state.screenWidth;
      },
      categoryFilters(state) {
        return state.categoryFilters;
      },
      productsOfCategory(state) {
        return state.productsOfCategory;
      },
      productsOfCategoryFilters(state) {
        let catFilter = state.categoryFilters;
        let res = state.productsOfCategory;


        if (catFilter['stock']) {
          if (catFilter['stock'] == 2) {
            res = res.filter((el) => {
              return el.stock && el.stock>0;
            });
          } else if (catFilter['stock'] == 3){
            res = res.filter((el) => {
              return !el.stock;
            });
          }
        }
        if (catFilter['q']) {
          res = res.filter((el) => {
            let name = el.name.toUpperCase();
            let sr = catFilter['q'].toUpperCase();
            return name.indexOf(sr) != -1;
          })
        }
        if (catFilter['group']) {
          if (catFilter['group'] == 3) {
            res = res.map((el) => {
              let newEl = {};
              Object.assign(newEl, el);              
              if (newEl.stock && newEl.stock>0) {
                newEl.group = {id: 1, name: "В наличии"};
              } else {
                newEl.group = {id: 2, name: "Под заказ"};
              }
              return newEl;
            });   
          } else if (catFilter['group'] != 1) {
            let grItem = state.topFilters['group'].items.find((el) => {
              return el.id == catFilter['group'];
            });
            if (grItem) {
              let arr = [];
              let undIt = {name: 'Неопределено'};
              let arrGr = [];
              res.forEach((el) => {
                let fPar = el.params.filter((it) => {
                  return it.param_type_id == grItem.param_type_id;
                })
                if (fPar.length) {
                  fPar.forEach((it2) => {
                    let newEl = {};
                    Object.assign(newEl, el);
                    let fElGr = arrGr.find((gr) => {
                      return gr.name == it2.value;
                    });
                    if (!fElGr) {
                      fElGr = {name: it2.value};
                      arrGr.push(fElGr);
                    }
                    newEl.group = fElGr;
                    arr.push(newEl);  
                  })
                } else {
                  let newEl = {};
                  Object.assign(newEl, el);
                  newEl.group = undIt;
                  arr.push(newEl);
                }
              });
              arrGr = arrGr.sort((a, b) => {
                if (a.name == b.name) {return 0;}
                return a.name<b.name ? -1 : 1;
              });
              arrGr.forEach((el, ind) => {
                el.id = ind+1;
              });
              undIt.id = arrGr.length+1;
              res = arr;
            }
          }
        }

        let sortItem;
        if (catFilter['order']) {
          sortItem = state.topFilters['order'].items.find((el) => {
            return el.id == catFilter['order'];
          });
        } else {
          sortItem = state.topFilters['order'].items[0];
        }
        if (sortItem['sort_field']) {
          res.sort((a, b)=>{
            if (a['group'] !== undefined) {
              if (a['group'] != b['group']) {
                return a['group'].id - b['group'].id;
              }
            }
            if (sortItem['sort_type'] == 'Число') {
              if (sortItem['sort_ord'] == 'DESC') {
                return (+b[sortItem['sort_field']]) - (+a[sortItem['sort_field']]);
              } else {
                return (+a[sortItem['sort_field']]) - (+b[sortItem['sort_field']]);
              }
            } else {
              if (a[sortItem['sort_field']] == b[sortItem['sort_field']]) {return 0;}
              if (sortItem['sort_ord'] == 'DESC') {
                return a[sortItem['sort_field']] < b[sortItem['sort_field']] ? 1 : -1;
              } else {
                return a[sortItem['sort_field']] < b[sortItem['sort_field']] ? -1 : 1;
              }
            }
          });
        }
        return res;
      },
      productsOfCategoryPage(state, getters) {
        let itFilter = getters.productsOfCategoryFilters;
        let catFilter = state.categoryFilters;
        let page = 0;
        if (catFilter['page']) {
          page = parseInt(catFilter['page']);
        }
        if (!page) {
          page = 1;
        }
        return itFilter.slice((page-1)*18,page*18);
      }
    }
  })
}