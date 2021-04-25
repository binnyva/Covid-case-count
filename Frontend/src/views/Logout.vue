<template>
  <div>
    <input type="button" class="btn btn-primary" value="Logout" @click="logout" /><br />
  </div>
</template>

<script>
  import firebase from 'firebase/app'
  import 'firebase/auth'
  
  export default {
    name: "Logout",
    methods: {
      // Code taken from https://blog.logrocket.com/vue-firebase-authentication/
      logout: function() {
        firebase
          .auth()
          .signOut()
          .then(() => {
            this.$router.replace({
              name: "Login"
            });
          });
      }
    }
  }
</script>