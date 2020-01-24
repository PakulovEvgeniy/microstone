export default {
	state: {
    curGroupIndex: 0
  },
  mutations: {
	  setCompare(state, payload) {
      this.state.compare.items = payload;
    },
    setCompareProducts(state, payload) {
      this.state.compare.products = payload;
    },
    setCurGroupIndex(state, payload) {
      state.curGroupIndex = payload;
    },
    delFromCompareProducts(state, payload) {
      let prod = this.state.compare.products;
      if (!prod.length) {return;}
      
      if (Array.isArray(payload)) {
        payload.forEach(el => {
          let fl = prod.findIndex((it) => {
            return it.id == el;
          });
          if (fl != -1) {
            prod.splice(fl, 1);
          }
        });
      } else {
        let fl = prod.findIndex((el) => {
          return el.id == payload;
        });
        if (fl != -1) {
          prod.splice(fl, 1);
        }
      }
    },
    changeDragIndex(state, payload) {
      let prod = this.state.compare.products;
      let indFrom = prod.findIndex(el => el.id==payload.currentId);
      let id_group = prod[indFrom].cg_id ? '2_'+prod[indFrom].cg_id : '1_' + prod[indFrom].cat_id;
      let indCur = prod.findIndex(el => el.id==payload.id);
      let needInd = indFrom;
      let c = 1;
      let count = payload.offsetIndex;
      let grp;
      while (true) {
        if (count == 0) {
          break;
        } else if (count>0) {
          if (needInd>=(prod.length-1)) {
            break;
          }
          if (needInd+c > (prod.length-1)) {
            break;
          }
          grp = prod[needInd+c].cg_id ? '2_'+prod[needInd+c].cg_id : '1_' + prod[needInd+c].cat_id;
          if (prod[needInd+c].isFixed || grp != id_group) {
            c++;
          } else {
            count--;
            needInd+=c;
            c = 1;
          }
        } else {
          if (needInd<=0) {
            break;
          }
          if (needInd-c < 0) {
            break;
          }
          grp = prod[needInd-c].cg_id ? '2_'+prod[needInd-c].cg_id : '1_' + prod[needInd-c].cat_id;
          if (prod[needInd-c].isFixed || grp != id_group) {
            c++;
          } else {
            count++;
            needInd-=c;
            c = 1;
          }
        }
      }
      let el = prod.splice(indCur, 1);
      prod.splice(needInd, 0, el[0]);
    }
	},
	getters: {
		compare(state, getters, rootState) {
      return rootState.compare;
    },
    countCompare(state, getters, rootState) {
      return getters.compare.items.length;
    },
    compareProducts(state, getters, rootState) {
      return getters.compare.products;
    },
    compareGroups(state, getters, rootState) {
      let res = [];
      rootState.compare.products.forEach((el) => {
        let id=0;
        let name = '';
        if (el.cg_id) {
          id = '2_'+el.cg_id;
          name = el.cg_name;
        } else {
          id = '1_' + el.cat_id;
          name = el.cat_name;
        }
        let elRes = res.find(el1 => el1.id == id);
        if (elRes) {
          elRes.items.push(el);
        } else {
          res.push({
            id: id,
            name: name,
            items: [el]
          });
        }
      });
      return res;
    },
    curGroup(state, getters, rootState) {
      if (!getters.compareGroups.length) {
        return undefined;
      }
      return getters.compareGroups[state.curGroupIndex];
    }
  },
  actions: {
    clearLocalCompare({commit, dispatch}, data) {
      localStorage.removeItem('compare');
      commit('setCompare', []);
    },
    clearGroup({commit, dispatch}, data) {
      let arr = data.items.map(el => el.id);
      dispatch('delFromLocalCompare', arr);
    },
    addToLocalCompare({commit, dispatch}, data) {
      let wish = localStorage.getItem('compare');
      if (!wish) {
        wish = [];
      } else {
        wish = JSON.parse(wish);
      }
      if (wish.indexOf(data) == -1) {
        wish.push(data);
      }
      localStorage.setItem('compare', JSON.stringify(wish));
      commit('setCompare', wish);
    },
    delFromLocalCompare({commit, dispatch}, data) {
      let wish = localStorage.getItem('compare');
      if (!wish) {
        return;
      } else {
        wish = JSON.parse(wish);
      }
      if (Array.isArray(data)) {
        data.forEach(el => {
          let ind = wish.indexOf(el);
          if (ind == -1) {
            return;
          }
          wish.splice(ind, 1);
        });
      } else {
        let ind = wish.indexOf(data);
        if (ind == -1) {
          return;
        }
        wish.splice(ind, 1); 
      }

      localStorage.setItem('compare', JSON.stringify(wish));
      commit('setCompare', wish);
      commit('delFromCompareProducts', data);
    },
    restoreCompare({commit, dispatch}, data) {
      let wish = localStorage.getItem('compare');
      if (!wish) {
        wish = [];
      } else {
        wish = JSON.parse(wish);
      }
      commit('setCompare', wish);
    }
  }
}