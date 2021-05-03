<template>
  <nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="container">
      <router-link class="navbar-brand" to="/">CCC</router-link>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-main" aria-controls="navbar-main" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse my-0" id="navbar-main">
        <ul class="navbar-nav ml-auto">
          <li v-if="loggedIn" class="nav-item"><router-link class="nav-link" to="/">Home</router-link></li>
          <li v-if="loggedIn" class="nav-item"><router-link class="nav-link" to="/settings">Settings</router-link></li>
          <li class="nav-item"><router-link class="nav-link" to="/about">About</router-link></li>
        </ul>
      </div>
    </div>
  </nav>

  <div id="content" class="container">
    <router-view/>
  </div>
</template>

<!--
  <div id="nav" v-if="loggedIn">
    <router-link to="/">Home</router-link> |
    <router-link to="/settings">Settings</router-link> | 
    <router-link to="/about">About</router-link>
  </div>
  <div class="container">
    <router-view/>
  </div>
  -->
<script>
export default {
  name: 'App',
  data() {
    return {
      loggedIn: this.$store.getters.loggedIn,
    }
  },
}
</script>

<style lang="scss">
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
}
#content {
  margin-top: 20px;
}

#nav {
  padding: 30px;

  a {
    font-weight: bold;
    color: #2c3e50;

    &.router-link-exact-active {
      color: #42b983;
    }
  }
}
</style>
