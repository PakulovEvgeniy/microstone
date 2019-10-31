export default {
	mutations: {
		setPageManuf (state, payload) {
     this.state.pageManuf = payload;
    },
    setCurBrand (state, payload) {
      this.state.curBrand = payload;
    },
    setBrands(state, payload) {
      this.state.brands.date = new Date();
      this.state.brands.items = payload;
    },
	},
	getters: {
		brands (state, getters, rootState) {
      return rootState.brands.items;
    },
    pageManuf(state, getters, rootState) {
      return rootState.pageManuf
    },
    curBrand(state, getters, rootState) {
      return rootState.curBrand;
    }
	}
}