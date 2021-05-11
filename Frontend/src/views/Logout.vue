<template>
  <div>
    <p>Please confirm that you wish to logout...</p>

    <input type="button" class="btn btn-danger" value="Logout" @click="logout" /><br />
  </div>
</template>

<script>
  import firebase from 'firebase/app'
  import 'firebase/auth'
  import http from '@/http'
  
  export default {
    name: "Logout",
    methods: {
      // Code taken from https://blog.logrocket.com/vue-firebase-authentication/
      logout: function() {
        if(this.$store.state.token) { // If there is a token, disable it.
          http.delete(`/users/${this.$store.state.user.uid}/devices/${this.$store.state.token}`).then(() => {
            firebase
              .auth()
              .signOut()
              .then(() => {
                this.$store.dispatch("fetchUser", null);
                this.$router.replace('login');
              });
          });
        } else {
          this.$store.dispatch("fetchUser", null);
          this.$router.replace('login');
        }
      }
    }
  }
</script>