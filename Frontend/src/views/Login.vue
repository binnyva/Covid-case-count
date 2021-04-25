<template>
  <div>
    <input type="email" name="email" v-model="email" placeholder="Email" class="form-control" /><br />
    <input type="password" name="password" v-model="password" class="form-control" /><br />
    <input type="submit" name="action" class="btn btn-primary" value="Login" />

    <p>
      OR<br />
      <input type="button" class="btn btn-primary" name="google-signin" value="Sign in with Google" @click="googleSignIn" /><br />
    </p>
  </div>
</template>

<script>
  import firebase from 'firebase/app'
  import 'firebase/auth'
  import http from '@/http'
  
  export default {
    name: "Login",
    data() {
      return {
        email: "",
        password: "",
      }
    },
    methods: {
      // Code taken from https://blog.logrocket.com/vue-firebase-authentication/
      googleSignIn: function() {
        let provider = new firebase.auth.GoogleAuthProvider();
        firebase
          .auth()
          .signInWithPopup(provider)
          .then((result) => {
            http.post('/users/login', result.user).then((res) => {
              console.log(res)
            })

            this.$router.replace('home')
          })
          .catch((err) => {
            console.log(err); // This will give you all the information needed to further debug any errors
          });
      }
    }
  }
</script>