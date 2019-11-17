export default {
	mutations: {
		setWishlist(state, payload) {
      this.state.wishlist.items = payload;
    }
	},
	getters: {
		wishlist(state, getters, rootState) {
      return rootState.wishlist;
    },
    countWishlist(state, getters, rootState) {
      return getters.wishlist.items.length;
    }
  },
  actions: {
    addToLocalWishlist({commit, dispatch}, data) {
      let wish = localStorage.getItem('wishlist');
      if (!wish) {
        wish = [];
      } else {
        wish = JSON.parse(wish);
      }
      if (wish.indexOf(data) == -1) {
        wish.push(data);
      }
      localStorage.setItem('wishlist', JSON.stringify(wish));
      commit('setWishlist', wish);
    }
  }
}