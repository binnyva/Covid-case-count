import { createStore } from 'vuex'

const store = createStore({
  state: {
    loggedIn: false,
    user: null,
    subscriptions: []
  },

  getters: {
    loggedIn(state) {
      return state.loggedIn
    },
    user(state){
      return state.user
    },
    subscriptions(state) {
      return state.subscriptions
    }
  },

  mutations: {
    SET_LOGGED_IN(state, value) {
      state.loggedIn = value;
    },
    SET_USER(state, data) {
      state.user = data;
    },
    SET_SUBSCRIPTIONS(state, subs) {
      state.subscriptions = subs
    }
  },

  actions: {
    fetchUser({ commit }, user) {
      commit("SET_LOGGED_IN", user !== null);
      if (user) {
        commit("SET_USER", {
          displayName: user.displayName,
          email: user.email,
          uid: user.uid,
          user_id: user.user_id
        });

        commit("SET_SUBSCRIPTIONS", user.subscriptions)
      } else {
        commit("SET_USER", null);
        commit("SET_SUBSCRIPTIONS", [])
      }
    },

    setSubscriptions({ commit }, subs) {
      commit("SET_SUBSCRIPTIONS", {
        subscriptions: subs
      })
    }
  }
});

export default store;