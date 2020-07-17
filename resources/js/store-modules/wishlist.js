import Vue from 'vue';

export default {
	mutations: {
		setWishlist(state, payload) {
      this.state.wishlist.items = payload;
    },
    setCurWishlist(state, payload) {
      this.state.wishlist.curItems = payload;
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
      let wish = this.state.auth ? this.state.wishlist.curItems : this.state.wishlist.items;
      if (!prod.length) {return;}
      let inWish = wish.find((el) => {
        return el.id == payload;
      });
      if (inWish) {return;}

      let fl = prod.findIndex((el) => {
        return el.id == payload;
      });
      if (fl != -1) {
        prod.splice(fl, 1);
      }
    },
    addToItemWish(state, payload) {
      this.state.wishlist.items.push(payload);
    },
    delFromItemWish(state, payload) {
      let ind = this.state.wishlist.items.findIndex((el) => {
        return el.id == payload.id && el.characteristic == payload.characteristic;
      });
      if (ind != -1) {
        //this.commit('delFromWishListProducts', this.state.wishlist.items[ind]);
        this.state.wishlist.items.splice(ind, 1);
      }
    },
    delFromCurItemWish(state, payload) {
      let ind = this.state.wishlist.curItems.findIndex((el) => {
        return el.id == payload.id && el.characteristic == payload.characteristic;
      });
      if (ind != -1) {
        //this.commit('delFromWishListProducts', this.state.wishlist.items[ind]);
        this.state.wishlist.curItems.splice(ind, 1);
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
      if (!rootState.wishlist.products.length) {
        return rootState.wishlist.products;
      }
      let items = getters.auth ? rootState.wishlist.curItems : rootState.wishlist.items;
      return items.map((el) => {
        let prd = rootState.wishlist.products.find((it) => {
          return it.id == el.id;
        });

        let price = prd.price;
        let charact_name  = '';
        if (prd.have_charact) {
          let pr = prd.characts_price.find((p) => {
            return p.id == el.characteristic;
          });
          let nc = prd.characts.find((p) => {
            return p.id == el.characteristic;
          });
          charact_name = nc ? nc.name : '';
          price = pr ? pr.price : 0;
        }
        return {
          id: el.id,
          characteristic: el.characteristic,
          charact_name: charact_name,
          ... prd,
          price: price
        }
      });
    },
    wishCurGroup(state, getters, rootState) {
      return rootState.wishlist.curGroup;
    },
    wishGroups(state, getters, rootState) {
      return rootState.wishlist.groups;
    },
    wishCurName(state, getters, rootState) {
      let el = rootState.wishlist.groups.find((el) => {
        return el.id == rootState.wishlist.curGroup;
      });
      return el ? el.name : '';
    },
    isInWish(state, getters, rootState) {
      return (it) => {
        return !!rootState.wishlist.items.find((el) => {
          return (el.id == it.id && el.characteristic == it.characteristic);
        });
      }
    },
    isInWishAll(state, getters, rootState) {
      return (it) => {
        if (it.have_charact) {
          
          return it.characts.every((cr) => {
            return !!rootState.wishlist.items.find((el) => {
              return (el.id == it.id && el.characteristic==cr.id);
            });
          });
        
        } else {
          return !!rootState.wishlist.items.find((el) => {
            return el.id == it.id
          });
        }
      }
    }
  },
  actions: {
    clearLocalWishlist({commit, dispatch}, data) {
      localStorage.removeItem('wishlist');
      commit('setWishlist', []);
      commit('setWishlistProducts', []);
    },
    clearServerWishlist({commit, dispatch}, data) {
      return dispatch('queryPostToServer', {
        url: '/account/wishlist/deletefromgroup',
        params: {
          group_id: data
        }
      });
    },
    addToWishList({commit, dispatch, rootState}, data) {
      if (rootState.auth) {
        dispatch('addToServerWishlist', data);
      } else {
        dispatch('addToLocalWishlist', data);
      }
    },
    addToLocalWishlist({commit, dispatch}, data) {
      let wish = localStorage.getItem('wishlist');
      if (!wish) {
        wish = [];
      } else {
        wish = JSON.parse(wish);
      }
      if (!wish.find((el) => {
        return el.id == data.id && el.characteristic == data.characteristic;
      })) {
        wish.push(data);
      }
      localStorage.setItem('wishlist', JSON.stringify(wish));
      commit('setWishlist', wish);
    },
    addToServerWishlist({commit, dispatch}, data) {
      return dispatch('queryPostToServer', {
        url: '/account/wishlist/add',
        params: {
          id: data.id,
          characteristic: data.characteristic
        }
      })
    },
    delFromWishList({commit, dispatch, rootState}, data) {
      if (rootState.auth) {
        dispatch('delFromServerWishlist', data);
      } else {
        dispatch('delFromLocalWishlist', data);
      }
    },
    delFromServerWishlist({commit, dispatch}, data) {
      return dispatch('queryPostToServer', {
        url: '/account/wishlist/delete',
        params: data
      })
    },
    delFromLocalWishlist({commit, dispatch}, data) {
      let wish = localStorage.getItem('wishlist');
      if (!wish) {
        return;
      } else {
        wish = JSON.parse(wish);
      }
      let ind = wish.findIndex((el) => {
        return el.id == data.id && el.characteristic == data.characteristic;
      });
      if (ind == -1) {
        return;
      }
      wish.splice(ind, 1); 
      localStorage.setItem('wishlist', JSON.stringify(wish));
      commit('setWishlist', wish);
      commit('delFromWishListProducts', data.id);
      
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