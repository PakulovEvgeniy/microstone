export default {
	mutations: {
		setUserPersonal(state, payload) {
      this.state.userPersonal.date = new Date();
      this.state.userPersonal.data = payload;
    },
    setUserContragents(state, payload) {
      this.state.userContragents.date = new Date();
      this.state.userContragents.items = payload;
    },
    setUserAddresses(state, payload) {
      this.state.userAddresses = payload;
    },
    setUserPersonalObj(state, payload) {
      for(let key in payload) {
        this.state.userPersonal.data[key] = payload[key];
      }
    }
	},
	getters: {
		userPersonal(state, getters, rootState) {
      return rootState.userPersonal.data;
    },
    userContragents(state, getters, rootState) {
      return rootState.userContragents.items;
    },
    userAddresses(state, getters, rootState) {
      return rootState.userAddresses;
    }
	}
}