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
    },
    addToItemCart(state, payload) {
      payload.forEach((el) => {
        let id = +el;
        if (id) {
          let ind = this.state.cart.items.indexOf(id);
          if (ind == -1) {
            this.state.cart.items.push(id);
          }
        }
      });
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
      if (wish.indexOf(data) == -1) {
        wish.push(data);
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
        if (wish.indexOf(el) == -1) {
          wish.push(el);
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
      let ind = wish.indexOf(data);
      if (ind == -1) {
        return;
      }
      wish.splice(ind, 1); 
      localStorage.setItem('cart', JSON.stringify(wish));
      commit('setCart', wish);
      commit('delFromCartProducts', data);
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
    addToCart({commit, dispatch}, data) {
      return dispatch('queryPostToServer', {
        url: '/account/cart/add',
        params: {
          id_list: data
        }
      });
    }
  }
}