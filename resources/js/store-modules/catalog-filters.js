import Vue from 'vue';

export default {
	mutations: {
		setFilters (state, payload) {
      this.state.filterItems = payload.filters;
      this.state.filterItemsDef = payload.filtersDef;
    },
    setCategoryFilters(state, payload) {
      if (this.state.categoryFilters[payload.name] === undefined) {
        Vue.set(this.state.categoryFilters, payload.name, payload.value);
      } else {
        this.state.categoryFilters[payload.name] = payload.value
      }
      if (payload.name == 'stock' && this.state.categoryFilters['page'] !== undefined) {
        this.state.categoryFilters['page'] = 1;
      }
    },
    setCategoryFiltersAll(state, payload) {
      let ob = {};
      let pat = /f\[(\d+)\]/;
      this.state.filterItems.forEach((el) => {
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
          let el = this.state.filterItems.find((it) => {
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
      this.state.categoryFilters = ob;
    },
    setOrders(state, payload) {
      this.state.topFilters['order'] =  payload;
      if (this.state.categoryFilters['order']) {
        let it = this.state.topFilters['order'].items.find((el) => {
          return el.id == this.state.categoryFilters['order'];
        });
        if (!it) {
          this.state.categoryFilters['order'] = this.state.topFilters['order'].items[0].id;
        }
      }
    },
    setGroups(state, payload) {
      this.state.topFilters['group'] =  payload;
      if (this.state.categoryFilters['group']) {
        let it = this.state.topFilters['group'].items.find((el) => {
          return el.id == this.state.categoryFilters['group'];
        });
        if (!it) {
          this.state.categoryFilters['group'] = this.state.topFilters['group'].items[0].id;
        }
      }
    }
	},
	getters: {
    topFilters(state, getters, rootState) {
      return rootState.topFilters
    },
    filterItems(state, getters, rootState) {
      return rootState.filterItems;
    },
    filterItemsDef(state, getters, rootState) {
      return rootState.filterItemsDef;
    },
    categoryFilters(state, getters, rootState) {
      return rootState.categoryFilters;
    }
	}
}