export default {
	mutations: {
		setWishlist(state, payload) {
      this.state.wishlist.items = payload;
    },
    setWishlistProducts(state, payload) {
      this.state.wishlist.products = payload;
    },
    delFromWishListProducts(state, payload) {
      let prod = this.state.wishlist.products;
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
		wishlist(state, getters, rootState) {
      return rootState.wishlist;
    },
    countWishlist(state, getters, rootState) {
      return getters.wishlist.items.length;
    },
    wishProducts(state, getters, rootState) {
      return getters.wishlist.products;
    }
  },
  actions: {
    clearLocalWishlist({commit, dispatch}, data) {
      localStorage.removeItem('wishlist');
      commit('setWishlist', []);
    },
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
    },
    delFromLocalWishlist({commit, dispatch}, data) {
      let wish = localStorage.getItem('wishlist');
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
      localStorage.setItem('wishlist', JSON.stringify(wish));
      commit('setWishlist', wish);
      commit('delFromWishListProducts', data);
    },
    restoreWishList({commit, dispatch}, data) {
      let wish = localStorage.getItem('wishlist');
      if (!wish) {
        wish = [];
      } else {
        wish = JSON.parse(wish);
      }
      commit('setWishlist', wish);
    }
  }
}