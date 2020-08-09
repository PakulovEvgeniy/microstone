export default {
	mutations: {
		setAuth (state, payload) {
        this.state.auth = payload;
    },
    setAuthFrom (state, payload) {
        this.state.authFrom = payload;
    },
    setTempPassword (state, payload) {
        this.state.tempPassword = payload;
    },
    setCsrf (state, payload) {
       this.state.csrf = payload;
    },
    setEmail (state, payload) {
      this.state.userEmail = payload;
    },
    setVerify (state, payload) {
      this.state.isVerify = payload;
    },
    setResetEmail (state, payload) {
      this.state.resetEmail = payload;
    }
	},
	getters: {
		csrf(state, getters, rootState) {
      return rootState.csrf;
    },
    auth (state, getters, rootState) {
      return rootState.auth;
    },
    authFrom (state, getters, rootState) {
      return rootState.authFrom;
    },
    tempPassword (state, getters, rootState) {
      return rootState.tempPassword;
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