export default {
	mutations: {
		setAuth (state, payload) {
        this.state.auth = payload;
    },
    setCsrf (state, payload) {
       this.state.csrf = payload;
    },
    setEmail (state, payload) {
      this.state.userEmail = payload;
    },
    setVerify (state, payload) {
      this.state.isVerify = payload;
    }
	},
	getters: {
		csrf(state, getters, rootState) {
      return rootState.csrf;
    },
    auth (state, getters, rootState) {
      return rootState.auth;
    },
    isVerify (state, getters, rootState) {
      return rootState.isVerify;
    },
    userEmail (state, getters, rootState) {
      return rootState.userEmail;
    },
    resetEmail (state, getters, rootState) {
      return rootState.resetEmail;
    },
	}
}