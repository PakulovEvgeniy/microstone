import {getListExcludeSome} from '../utils/utils.js';

export default {
	state: {
    curGroupIndex: 0,
    exclude1Id: 1,
    exclude2Id: 0,
    curMobile1: 0,
    curMobile2: 0,
    curCompareSlide: 0,
    onlyDifferent: false
  },

  mutations: {
	  setCompare(state, payload) {
      this.state.compare.items = payload;
    },
    setCurCompareSlide(state, payload) {
      state.curCompareSlide = payload;
    },
    setCurMobile1(state, payload) {
      state.curMobile1 = payload;
    },
    setCurMobile2(state, payload) {
      state.curMobile2 = payload;
    },
    setExclude1Id(state, payload) {
      state.exclude1Id = payload;
    },
    setExclude2Id(state, payload) {
      state.exclude2Id = payload;
    },
    setOnlyDifferent(state, payload) {
      state.onlyDifferent = payload;
    },
    setCompareProducts(state, payload) {
      this.state.compare.products = payload;
    },
    setCurGroupIndex(state, payload) {
      state.curGroupIndex = payload;
      state.exclude1Id = 1;
      state.exclude2Id = 0;
      state.curMobile1 = 0;
      state.curMobile2 = 0;
      state.curCompareSlide = 0;
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
    compareEqual(state, getters, rootState) {
      let it = getters.compare.items;
      let prod = getters.compare.products;
      if (!it.length) { return false; }
      if (it.length != prod.length) { return false; }
      for (let i = 0; i < it.length; i++) {
        let elem = prod.find((el) => {
          return el.id == it[i];
        });
        if (!elem) { return false; }
      }
      return true;
    },
    countCompare(state, getters, rootState) {
      return getters.compare.items.length;
    },
    compareProducts(state, getters, rootState) {
      return getters.compare.products;
    },
    itemFixed(state, getters, rootState) {
      if (getters.smallScreen) { return [];}
      if(getters.curGroup) {
        return getters.curGroup.items.filter(el => el.isFixed);
      }
      return [];
    },
    itemNotFixed(state, getters, rootState) {
      if (getters.smallScreen) { return []; }
      if (getters.curGroup) {
        return getters.curGroup.items.filter(el => !el.isFixed);
      }
      return [];
    },
    perPageCustom(state, getters, rootState) {
      let c = 2;
      if (!getters.smallScreen) {
        c = c+=getters.getScreenState;
      } else {
        return c;
      }
      return Math.max(c-getters.itemFixed.length,0);
    },
    list1Mobile(state, getters, rootState) {
      if (!getters.smallScreen) { return; }
      return getListExcludeSome(state.exclude1Id, getters.curGroup);
    },
    list2Mobile(state, getters, rootState) {
      if (!getters.smallScreen) { return; }
      return getListExcludeSome(state.exclude2Id, getters.curGroup);
    },
    listParams(state, getters, rootState) {
      let res = [];
      if (getters.smallScreen) {
        if (getters.list1Mobile && getters.list1Mobile.length) {
          res.push(getters.list1Mobile[state.curMobile1]);
        }
        if (getters.list2Mobile && getters.list2Mobile.length) {
          res.push(getters.list2Mobile[state.curMobile2]);
        }
      } else {
        let c = 2 + getters.getScreenState;
        if (getters.itemFixed && getters.itemFixed.length>0) {
          for (var i = 0; i < getters.itemFixed.length; i++) {
            res.push(getters.itemFixed[i]);
            if (--c == 0) break;
          }
        }
        if (c>0 && getters.itemNotFixed && getters.itemNotFixed.length>0) {
          for (var i = state.curCompareSlide; i < getters.itemNotFixed.length; i++) {
            res.push(getters.itemNotFixed[i]);
            if (--c == 0) break;
          }
        }
        if (c>0) {
          while (c--) {
            res.push(undefined);
          }
        }
      }
      return res;
    },
    tableOfParams(state, getters, rootState) {
      let parms = [];
      getters.listParams.forEach((el) => {
        if (el === undefined) {return;}
        el.product_params.forEach((it) => {
          let par = parms.find((p) => {
            return it.param_type_id == p.param_type_id;
          });
          if (!par) {
            par = {
              param_type_id: it.param_type_id,
              name: it.name,
              kod_sort: it.kod_sort,
              description: it.description
            }
            parms.push(par);
          }
        })
      });
      parms.forEach((el) => {
        let par_val = getters.listParams.map((it) => {
          if (it === undefined) { return undefined; }
          let p = it.product_params.find(pp => el.param_type_id == pp.param_type_id);
          return p ? p.value : '';
        });
        el.params = par_val;
        el.notDifferent = true;
        if (par_val.length) {
          el.notDifferent = par_val.every((el) =>  {
            if (el === undefined) { return true; }
            return el == par_val[0]
          });
        }
      })
      return parms.sort((el1, el2) => {
        return el1.kod_sort - el2.kod_sort;
      });
    },
    baseWidth(state, getters, rootState) {
      if (getters.smallScreen) {
        return 0;
      }
      return 220;
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
      return res.sort((el1, el2) => {
        if (el1.name > el2.name) {
          return 1;
        } else if (el1.name < el2.name) {
          return -1;
        } else {
          return 0;
        }
      });
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
      commit('setCurGroupIndex', 0);
    },
    addToLocalCompare({commit, dispatch}, data) {
      let wish = localStorage.getItem('compare');
      if (!wish) {
        wish = [];
      } else {
        wish = JSON.parse(wish);
      }
      if (Array.isArray(data)) {
        data.forEach(el => {
          if (wish.indexOf(el) == -1) {
            wish.push(el);
          }
        })
      } else {
        if (wish.indexOf(data) == -1) {
          wish.push(data);
        }
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