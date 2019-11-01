export default {
	mutations: {
		setCatalog(state, payload) {
      this.state.catalog.date = new Date();
      this.state.catalog.items = payload;
    },
    setProductsOfCategoryPage(state, payload) {
      this.state.productsOfCategoryPage = payload;
    }
	},
	getters: {
		getCatalog (state, getters, rootState) {
      return rootState.catalog;
    },
    productsOfCategoryPage(state, getters, rootState) {
      return rootState.productsOfCategoryPage.items
    },
    totalQty(state, getters, rootState) {
      return rootState.productsOfCategoryPage.totalQty;
    },
    qtyOnPage(state, getters, rootState) {
      return +rootState.settings.prod_count;
    }
	}
}