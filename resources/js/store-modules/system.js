let ta=undefined;
export default {
	getters: {
		settings (state, getters, rootState) {
      return rootState.settings;
    },
    visBacdrop (state, getters, rootState) {
      return rootState.visBacdrop;
    },
    scrolled (state, getters, rootState) {
      return rootState.scrolled;
    },
    idTimeStartBack (state, getters, rootState) {
      return rootState.idTimeStartBack;
    },
    idTimeStopBack (state, getters, rootState) {
      return rootState.idTimeStopBack;
    },
    nonVisibleMain (state, getters, rootState) {
      return rootState.nonVisibleMain;
    },
    nonVisibleAside (state, getters, rootState) {
      return rootState.nonVisibleAside;
    },
    isBottomMenuFixed (state, getters, rootState) {
      return rootState.scrolled>40;
    },
    getScreenState(state, getters, rootState) {
      if (rootState.screenWidth==0 || rootState.screenWidth>1200) {
        return 3
      } else if (rootState.screenWidth<993) {
        return 1
      } else {
        return 2
      }
    },
    smallScreen(state, getters, rootState) {
      return !(rootState.screenWidth==0 || rootState.screenWidth>767); 
    },
   	screenWidth(state, getters, rootState) {
      return rootState.screenWidth;
    },
    screenHeight(state, getters, rootState) {
      return rootState.screenHeight;
    },
    activeWait(state, getters, rootState) {
      return rootState.activeWait;
    },
    bodyBlocked(state, getters, rootState) {
      return rootState.bodyBlocked;
    }
	},
	mutations: {
		setSettings (state, payload) {
      this.state.settings = payload;
    },
    setScrolled (state, payload) {
      this.state.scrolled = payload;
    },
    setScreenWidth (state, payload) {
      this.state.screenWidth = payload;
    },
    setScreenHeight (state, payload) {
      this.state.screenHeight = payload;
    },
    setVisBacdrop (state, payload) {
      this.state.visBacdrop = payload;
    },
    setIdTimeStartBack (state, payload) {
      this.state.idTimeStartBack = payload;
    },
    setIdTimeStopBack (state, payload) {
      this.state.idTimeStopBack = payload;
    },
    setNonVisibleMain (state, payload) {
      this.state.nonVisibleMain = payload;
    },
    setNonVisibleAside (state, payload) {
      this.state.nonVisibleAside = payload;
    },
    setActiveWait(state, payload) {
      if (typeof(payload) == 'boolean') {
        this.state.activeWait.wait = payload;
      } else {
        this.state.activeWait.wait = payload.wait;
        this.state.activeWait.block = payload.block;
      }
    },
    setActiveBlock(state, payload) {
      this.state.activeWait.block = payload
    },
    setBodyBlocked(state, payload) {
      this.state.bodyBlocked = payload;
    }
	},
	actions: {
      queryForApp({commit, dispatch}, data) {
        data.notwait = true;
        if (data.notcatch === undefined) {
          data.notcatch = true;
        }
        if (!data.params) {
          data.params = {};
        }
        return dispatch('queryGetToServer', data);
      },
      queryPostToServer({commit, dispatch}, data) {
        dispatch('showWait');
        return axios.post(data.url, data.params)
          .then(response => {
            dispatch('closeWait');
            if (response.data.status=='OK' && data.successAction) {
              data.successAction(response.data.succesParams);
            }
            if (response.data.status=='ER' && data.errorAction) {
              data.errorAction();
            }
            dispatch('setParams', response.data);
          })
          .catch(e => {
            if (data.errorAction) {
              data.errorAction();
            }
            dispatch('showError', e);
          })
      },
      queryGetToServer({commit, rootState, dispatch}, data) {
        if (data.cancash) {
          if (rootState[data.key].items.length) {
            if (!rootState[data.key].date) {
              rootState[data.key].date = new Date();
              return;
            }
            let nDat = new Date();
            let dif = (nDat.getTime() - rootState[data.key].date.getTime())/1000;
            if (dif<=3600) {
              return;
            }
          }
        }

        if(!data.notwait) {
          dispatch('showWait');
        }

        let gt = axios.get(data.url, {params: data.params})
          .then(response => {
            if(!data.notwait) {
              dispatch('closeWait');
            }
            if (response.data.status=='OK' && data.successAction) {
              data.successAction(response.data);
            }
            if (response.data.status=='ER' && data.errorAction) {
              data.errorAction(response.data);
            }
            dispatch('setParams', response.data);
          })
        if (data.notcatch) {
          return gt;
        } else {
          return gt.catch(e => {
            if (data.errorAction) {
              data.errorAction(e);
            }
            dispatch('showError', e);
          })
        }
      },
      setParams({ commit }, data) {
        let dat = data;
        if (dat.status && dat.status == 'OK') {
          if (dat.email) {
            commit('setAuth', true);
            commit('setEmail', dat.email);  
          }
          if (dat.isVerify) {
            commit('setVerify', dat.isVerify);
          }
          if (dat.data) {
            for(let key in dat.data) {
              commit(key, dat.data[key]);
            }
          }
        }
        if (dat.csrf) {
            commit('setCsrf', dat.csrf);
            axios.defaults.headers.common['X-CSRF-TOKEN'] = dat.csrf;
        }
        if (dat.redirectTo) {
          $router.push(dat.redirectTo);
        }
        if (dat.error) {
          $notify("alert", dat.error, "error");
        }
        if (dat.message) {
          $notify("alert", dat.message, "success");
        }
      },
      showWait({commit}, data) {
        commit('setActiveBlock', true);
        ta = setTimeout(() => {
          commit('setActiveWait', true);
        }, 200);
      },
      closeWait({commit}, data) {
        clearTimeout(ta);
        commit('setActiveWait', {'wait': false, 'block': false});
      },
      showError({commit}, data) {
        let e = data;
        clearTimeout(ta);
        commit('setActiveWait', {'wait': false, 'block': false});
        console.dir(e);
        if (e.response && e.response.data && e.response.data.errors) {
          let err = e.response.data.errors;
          if (err) {
              for(let el in err) {
                  $notify("alert", err[el][0], "error");
                  break;
              }
          }
        } else if (e.response && (e.response.status==419 || e.response.status==401)) {
          if ($router.history && $router.history.pending && $router.history.pending.path) {
            window.location.href=$router.history.pending.path;
          } else {
            window.location.href='/';
          }
        } else {
            $notify("alert", e.message, "error");
        }
      }
    }
}