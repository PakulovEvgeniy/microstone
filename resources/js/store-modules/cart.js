export default {

	mutations: {
	  setCart(state, payload) {
      this.state.cart.items = payload;
    },
    setCartProducts(state, payload) {
      this.state.cart.products = payload;
    },
    delFromCartProducts(state, payload) {
      let prod = this.state.cart.products;
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
		cart(state, getters, rootState) {
      return rootState.cart;
    },
    cartQty(state, getters, rootState) {
      return getters.cart.items.length;
    },
    cartProducts(state, getters, rootState) {
      return getters.cart.products;
    },
    isInCart(state, getters, rootState) {
      return (it) => {
        return !!rootState.cart.items.find((el) => {
          return (el.id == it.id && el.characteristic == it.characteristic);
        });
      }
    },
    isInCartAll(state, getters, rootState) {
      return (it) => {
        if (it.have_charact) {
          return it.characts.every((cr) => {
            return !!rootState.cart.items.find((el) => {
              return (el.id == it.id && el.characteristic==cr.id);
            });
          });
        } else {
          return !!rootState.cart.items.find((el) => {
            return el.id == it.id
          });
        }
      }
    }
  },
  actions: {
    clearLocalCart({commit, dispatch}, data) {
      localStorage.removeItem('cart');
      commit('setCart', []);
    },
    addToLocalCart({commit, dispatch}, data) {
      let wish = localStorage.getItem('cart');
      if (!wish) {
        wish = [];
      } else {
        wish = JSON.parse(wish);
      }
      let item = wish.find((it) => {
        return it.id == data.id && it.characteristic == data.characteristic;
      });
      if (!item) {
        wish.push(data);
      } else {
        item.qty += (+data.qty);
      }
      localStorage.setItem('cart', JSON.stringify(wish));
      commit('setCart', wish);
    },
    addArrayToLocalCart({commit, dispatch}, data) {
      let wish = localStorage.getItem('cart');
      if (!wish) {
        wish = [];
      } else {
        wish = JSON.parse(wish);
      }
      data.forEach((el) => {
        let item = wish.find((it) => {
          return it.id == el.id && it.characteristic == el.characteristic;
        });
        if (!item) {
          wish.push(el);
        } else {
          item.qty += (+el.qty);
        }
      });
      
      localStorage.setItem('cart', JSON.stringify(wish));
      commit('setCart', wish);
    },
    delFromLocalCart({commit, dispatch}, data) {
      let wish = localStorage.getItem('cart');
      if (!wish) {
        return;
      } else {
        wish = JSON.parse(wish);
      }
      let item = wish.find((it) => {
        return it.id == data.id && it.characteristic == data.characteristic;
      });
      if (!item) {
        return;
      }
      let ind = wish.indexOf(item);
      wish.splice(ind, 1); 
      localStorage.setItem('cart', JSON.stringify(wish));
      commit('setCart', wish);
      commit('delFromCartProducts', item.id);
    },
    restoreCart({commit, dispatch}, data) {
      let wish = localStorage.getItem('cart');
      if (!wish) {
        wish = [];
      } else {
        wish = JSON.parse(wish);
      }
      commit('setCart', wish);
    },
    addToCart({commit, dispatch, rootState}, data) {
      if (rootState.auth) {
        return dispatch('queryPostToServer', {
          url: '/account/cart/add',
          params: {
            id_list: data
          }
        });
      } else {
        dispatch('addArrayToLocalCart', data);
      }
      
    }
  }
}