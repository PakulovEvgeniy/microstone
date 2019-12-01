export default {
	mutations: {
	  setCompare(state, payload) {
      this.state.compare.items = payload;
    },
    setCompareProducts(state, payload) {
      this.state.compare.products = payload;
    },
    delFromCompareProducts(state, payload) {
      let prod = this.state.compare.products;
      if (!prod.length) {return;}
      let fl = prod.findIndex((el) => {
        return el.id == payload;
      });
      if (fl != -1) {
        prod.splice(fl, 1);
      }
    }
	},
	getters: {
		compare(state, getters, rootState) {
      return rootState.compare;
    },
    countCompare(state, getters, rootState) {
      return getters.compare.items.length;
    },
    compareProducts(state, getters, rootState) {
      return getters.compare.products;
    }
  },
  actions: {
    clearLocalCompare({commit, dispatch}, data) {
      localStorage.removeItem('compare');
      commit('setCompare', []);
    },
    addToLocalCompare({commit, dispatch}, data) {
      let wish = localStorage.getItem('compare');
      if (!wish) {
        wish = [];
      } else {
        wish = JSON.parse(wish);
      }
      if (wish.indexOf(data) == -1) {
        wish.push(data);
      }
      localStorage.setItem('compare', JSON.stringify(wish));
      commit('setCompare', wish);
    },
    delFromLocalCompare({commit, dispatch}, data) {
      let wish = localStorage.getItem('compare');
      if (!wish) {
        return;
      } else {
        wish = JSON.parse(wish);
      }
      let ind = wish.indexOf(data);
      if (ind == -1) {
        return;
      }
      wish.splice(ind, 1); 
      localStorage.setItem('compare', JSON.stringify(wish));
      commit('setCompare', wish);
      commit('delFromCompareProducts', data);
    },
    restoreCompare({commit, dispatch}, data) {
      let wish = localStorage.getItem('compare');
      if (!wish) {
        wish = [];
      } else {
        wish = JSON.parse(wish);
      }
      commit('setCompare', wish);
    }
  }
}