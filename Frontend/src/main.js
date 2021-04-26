import { createApp } from 'vue'

import 'bootstrap'
import 'bootstrap/dist/css/bootstrap.min.css'

import './registerServiceWorker'

import App from './App.vue'

import router from './router'
import store from "./store";

import { firebase } from './firebase'

firebase.auth().onAuthStateChanged(user => {
  store.dispatch("fetchUser", user);
})

createApp(App).use(router).use(store).mount('#app')

// Following this Tutorial : https://www.youtube.com/watch?v=q5J5ho7YUhA&t=331s 