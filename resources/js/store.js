import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

import axios from 'axios';

export function createStore () {
  return new Vuex.Store({
    state: {
      name: '',
      auth: false,
      csrf: '',
      userEmail: 'info@microstone.ru',
      settings: {},
      scrolled: 0,
      screenWidth: 0,
      nonVisibleMain: false
    },
    actions: {
      setName ({ commit }, page) {
        return axios.get(`/api/name/${page}`).then(({ data }) => commit('setName', data));
      }
    },
    mutations: {
      setName (state, payload) {
        state.name = payload;
      },
      setSettings (state, payload) {
        state.settings = payload;
      },
      setScrolled (state, payload) {
        state.scrolled = payload;
      },
      setScreenWidth (state, payload) {
        state.screenWidth = payload;
      },
      setNonVisibleMain (state, payload) {
        state.nonVisibleMain = payload;
      }
    },
    getters: {
      settings (state) {
        return state.settings;
      },
      auth (state) {
        return state.auth;
      },
      userEmail (state) {
        return state.userEmail;
      },
      nonVisibleMain (state) {
        return state.nonVisibleMain;
      },
      isBottomMenuFixed (state) {
        return state.scrolled>40;
      },
      getScreenState(state) {
        if (state.screenWidth==0 || state.screenWidth>1199) {
          return 3
        } else if (state.screenWidth<992) {
          return 1
        } else {
          return 2
        }
      }
    }
  })
}