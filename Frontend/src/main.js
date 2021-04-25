import { createApp } from 'vue'

import firebase from 'firebase/app'
import 'firebase/auth'
// import './registerServiceWorker'

import App from './App.vue'

import router from './router'
import store from "./store";

import 'bootstrap'
import 'bootstrap/dist/css/bootstrap.min.css'

const firebaseConfig = {
  apiKey: "AIzaSyCEyqGU5Ia1BNcP_9xg3yMab8y6q1W5OdE",
  authDomain: "covid-case-count.firebaseapp.com",
  projectId: "covid-case-count",
  storageBucket: "covid-case-count.appspot.com",
  messagingSenderId: "1009940078275",
  appId: "1:1009940078275:web:c03080e0c538e873834c76",
  measurementId: "G-L7SZBE7T74"
};

firebase.initializeApp(firebaseConfig);

firebase.auth().onAuthStateChanged(user => {
  store.dispatch("fetchUser", user);
})

createApp(App).use(router).use(store).mount('#app')

// Following this Tutorial : https://www.youtube.com/watch?v=q5J5ho7YUhA&t=331s 