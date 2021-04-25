import { createApp } from 'vue'

import firebase from 'firebase/app'
import 'firebase/auth'

import 'bootstrap'
import 'bootstrap/dist/css/bootstrap.min.css'

// import './registerServiceWorker'

import App from './App.vue'

import router from './router'
import store from "./store";

import { firebase_config } from '@/utils/secret'

firebase.initializeApp(firebase_config);

firebase.auth().onAuthStateChanged(user => {
  store.dispatch("fetchUser", user);
})

createApp(App).use(router).use(store).mount('#app')

// Following this Tutorial : https://www.youtube.com/watch?v=q5J5ho7YUhA&t=331s 