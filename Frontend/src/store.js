import { createStore } from 'vuex'
import createPersistedState from "vuex-persistedstate";

const store = createStore({
  state: {
    loggedIn: false,
    user: null,
    subscriptions: [],
    locations: []
  },
  plugins: [createPersistedState()],

  getters: {
    loggedIn(state) {
      return state.loggedIn
    },
    user(state){
      return state.user
    },
    subscriptions(state) {
      return state.subscriptions
    },
    locations(state) {
      return state.locations
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
    },
    SET_LOCATIONS(state, locs) {
      state.locations = locs
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

        if(user.subscriptions) commit("SET_SUBSCRIPTIONS", user.subscriptions)
        if(user.locations) commit("SET_LOCATIONS", user.locations)
      } else {
        commit("SET_USER", null);
        commit("SET_SUBSCRIPTIONS", [])
        commit("SET_LOCATIONS", [])
      }
    },

    setUser({ commit }, user) {
      commit("SET_USER", {
        displayName: user.displayName,
        email: user.email,
        uid: user.uid,
        user_id: user.user_id
      });

      commit("SET_SUBSCRIPTIONS", user.subscriptions)
      commit("SET_LOCATIONS", user.locations)
    },

    setSubscriptions({ commit }, subs) {
      commit("SET_SUBSCRIPTIONS", subs )
    },

    setLocations({ commit }, locs) {
      commit("SET_LOCATIONS", locs)
    }
  }
});

export default store;