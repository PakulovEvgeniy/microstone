export default {
	mutations: {
		
	},
	getters: {
		wishlist(state, getters, rootState) {
      return rootState.wishlist;
    },
    countWishlist(state, getters, rootState) {
      return getters.wishlist.items.length;
    }
	}
}