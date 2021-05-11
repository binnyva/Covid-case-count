<template>
  <div>
    <p>Please sign in to use the app...</p>
    <input type="button" class="btn btn-primary" name="google-signin" value="Sign in with Google" @click="googleSignIn" /><br />
  </div>
</template>

<script>
  import firebase from 'firebase/app'
  import 'firebase/auth'
  import http from '@/http'
  
  export default {
    name: "Login",
    methods: {
      // Code taken from https://blog.logrocket.com/vue-firebase-authentication/
      googleSignIn: function() {
        let provider = new firebase.auth.GoogleAuthProvider();
        firebase
          .auth()
          .signInWithPopup(provider)
          .then((result) => {
            http.post('/users/login', result.user).then((res) => {
              this.$store.dispatch("setUser", res.data.data)
              this.$router.replace('home')
            })
          })
          .catch((err) => {
            console.log(err); // This will give you all the information needed to further debug any errors
          });
      }
    }
  }
</script>