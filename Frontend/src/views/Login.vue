<template>
  <div>
    <p class="lead">Covid Case Count will <strong>show the Covid-19 Case counts in the places of your interest</strong>. 
      This can be states, districts or even the entire Country. For more details, see the <router-link to="about">About section</router-link>.</p>

    <p>Update: This app is currently <strong>inactive</strong>(as of 2021 November). The source of the data for this app is <a href="https://covid19india.org">Covid19India</a> - and they have <a href="https://blog.covid19india.org/2021/08/07/end/">shut down opererations</a>. CCC will be available once I find another data source. Sorry about the inconvenience.</p>

    <!-- <p>Please sign in to use the app...</p>
    <input type="button" class="btn btn-primary" name="google-signin" value="Sign in with Google" @click="googleSignIn" /><br /> -->
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