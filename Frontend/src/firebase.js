import firebase from 'firebase/app'
import 'firebase/auth'
import 'firebase/messaging'
import store from './store'
import http from "./http";

import { firebase_config, firebase_web_push_key } from '@/utils/secret'

firebase.initializeApp(firebase_config);

let messaging = false
try {
  messaging = firebase.messaging()
} catch (error) {
  console.error("Can't use Firebase SDK: " + error)
}

const requestPermission = async () => {
  let done = false;
  const currentToken = await messaging.getToken({ vapidKey: firebase_web_push_key });

  if (currentToken) {
    const res = await http.post(`/users/${store.state.user.uid}/devices`, {
      token: currentToken
    });

    if(res.data.status == "success") {
      done = true
    }
  }

  return done
}

export { firebase, messaging, requestPermission } //, requestPermission, onMessage, convertNotificationPayload };
