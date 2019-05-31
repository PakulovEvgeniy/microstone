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
      topFilters: [
        {
          name: 'order',
          caption: 'Сортировать:',
          items: [
            {
              id: 1,
              name: 'По продажам'
            },
            {
              id: 2,
              name: 'По возрастанию цены'
            },
            {
              id: 3,
              name: 'По убыванию цены'
            },
            {
              id: 4,
              name: 'По наименованию'
            },
            {
              id: 5,
              name: 'По рейтингу'
            }
          ]
        },
        {
          name: 'group',
          caption: 'Группировать:',
          items: [
            {
              id: 1,
              name: 'Без группировки'
            },
            {
              id: 2,
              name: 'По производителю'
            },
            {
              id: 3,
              name: 'По наличию'
            }
          ]
        },
        {
          name: 'stock',
          caption: 'Наличие:',
          items: [
            {
              id: 1,
              name: 'В наличии и под заказ'
            },
            {
              id: 2,
              name: 'В наличии'
            },
            {
              id: 3,
              name: 'Под заказ'
            }
          ]
        }
      ]
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
        state.categoryFilters[payload.name] = payload.value
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
      screenWidth(state) {
        return state.screenWidth;
      },
      categoryFilters(state) {
        return state.categoryFilters;
      }
    }
  })
}