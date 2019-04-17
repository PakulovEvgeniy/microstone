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
      settings: {}
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
      }
    }
  })
}