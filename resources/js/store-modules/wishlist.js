export default {
	mutations: {
		setWishlist(state, payload) {
      this.state.wishlist.items = payload;
    },
    setWishlistProducts(state, payload) {
      this.state.wishlist.products = payload;
    },
    setWishGroup(state, payload) {
      this.state.wishlist.curGroup = payload;
    },
    setWishName(state, payload) {
      this.state.wishlist.curName = payload;
    },
    setWishGroups(state, payload) {
      this.state.wishlist.groups = payload;
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
    },
    addToItemWish(state, payload) {
      this.state.wishlist.items.push(+payload);
    },
    delFromItemWish(state, payload) {
      let ind = this.state.wishlist.items.findIndex((el) => {
        return el == payload;
      });
      if (ind != -1) {
        this.commit('delFromWishListProducts', this.state.wishlist.items[ind]);
        this.state.wishlist.items.splice(ind, 1);
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
    },
    wishCurGroup(state, getters, rootState) {
      return rootState.wishlist.curGroup;
    },
    wishCurName(state, getters, rootState) {
      return rootState.wishlist.curName;
    },
    wishGroups(state, getters, rootState) {
      return rootState.wishlist.groups;
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
    addToServerWishlist({commit, dispatch}, data) {
      return dispatch('queryPostToServer', {
        url: '/account/wishlist/add',
        params: {
          id: data
        }
      })
    },
    delFromServerWishlist({commit, dispatch}, data) {
      return dispatch('queryPostToServer', {
        url: '/account/wishlist/delete',
        params: {
          id: data
        }
      })
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