export default {
  state: {
    showCartInfo: true
  },
	mutations: {
	  setCart(state, payload) {
      this.state.cart.items = payload;
    },
    setCartProducts(state, payload) {
      this.state.cart.products = payload;
    },
    setShowCartInfo(state, payload) {
      state.showCartInfo = payload;
    },
    delFromCartProducts(state, payload) {
      let prod = this.state.cart.products;
      let items = this.state.cart.items;
      if (!prod.length) {return;}

      payload.forEach((el) => {
        let it = items.find((e) => {
          return e.id == el.id;
        })
        if (!it) {
          let prInd = prod.findIndex((e) => {
            return e.id == el.id;
          });
          if (prInd != -1) {
            prod.splice(prInd, 1);
          } 
        }
      });
    }
	},
	getters: {
		cart(state, getters, rootState) {
      return rootState.cart;
    },
    showCartInfo(state, getters, rootState) {
      return state.showCartInfo;
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
          if (it.characteristic) {
            return !!rootState.cart.items.find((el) => {
              return el.id == it.id && el.characteristic == it.characteristic;
            });
          } else {
            return it.characts.every((cr) => {
              return !!rootState.cart.items.find((el) => {
                return (el.id == it.id && el.characteristic==cr.id);
              });
            });
          }
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
    clearCart({commit, dispatch, rootState}, data) {
      dispatch('clearLocalCart');
      commit('setCartProducts', []);
      if (rootState.auth) {
        return dispatch('queryPostToServer', {
          url: '/account/cart/clear',
          params: {}
        })
      }
    },
    deleteItemFromCart({commit, dispatch, rootState}, data) {
      dispatch('delFromLocalCart', data);
      if (rootState.auth) {
        return dispatch('queryPostToServer', {
          url: '/account/cart/delete',
          params: {
            id_list: data
          }
        }).then(resp => {
          commit('delFromCartProducts', data);
        });
      } else {
        dispatch('restoreCart');
        commit('delFromCartProducts', data)
      }
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
    editArrayToLocalCart({commit, dispatch}, data) {
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
          item.qty = (+el.qty);
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
      let flSave = false;
      data.forEach((el) => {
        let item = wish.find((it) => {
          return it.id == el.id && it.characteristic == el.characteristic;
        });
        if (item) {
          let ind = wish.indexOf(item);
          wish.splice(ind, 1);
          flSave = true;
        }
      });
      if (flSave) {
        localStorage.setItem('cart', JSON.stringify(wish));
      }
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
      
    },
    editToCart({commit, dispatch, rootState}, data) {
      if (rootState.auth) {
        return dispatch('queryPostToServer', {
          url: '/account/cart/edit',
          params: {
            id_list: data
          }
        });
      } else {
        dispatch('editArrayToLocalCart', data);
      }
      
    }
  }
}