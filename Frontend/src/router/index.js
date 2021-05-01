import { createRouter, createWebHashHistory } from 'vue-router'
// import firebase from 'firebase/app'
// import 'firebase/auth'
import store from "../store"

import Home from '../views/Home.vue'
import Settings from '../views/Settings.vue'
import Login from '../views/Login.vue'
import Logout from '../views/Logout.vue'
import Subscriptions from "../views/Subscriptions.vue"

const routes = [
  {
    path: '/home',
    redirect: '/'
  },

  {
    path: '/',
    name: 'Home',
    component: Home,
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/settings',
    name: 'Settings',
    component: Settings,
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/subscriptions',
    name: 'Subscriptions',
    component: Subscriptions,
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/login',
    name: 'Login',
    component: Login
  },
  {
    path: '/logout',
    name: 'Logout',
    component: Logout
  },
  {
    path: '/about',
    name: 'About',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    // component: () => import(/* webpackChunkName: "about" */ '../views/About.vue')
  }
]

const router = createRouter({
  history: createWebHashHistory(),
  routes
})

router.beforeEach((to, from, next) => {
  // const currentUser = firebase.auth().currentUser;
  const loggedIn = store.getters.loggedIn
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth);

  if (requiresAuth && !loggedIn) next('login');
  else next();
});

export default router
