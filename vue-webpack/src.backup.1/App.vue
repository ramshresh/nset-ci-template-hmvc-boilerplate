<template>
  <div id="app">
       <a href ="/">Home</a>
       <a href ="/users">Users</a>
       <hr>
       Single Page
       <router-link to="/">Home</router-link>
       <router-link to="/users/10">Users</router-link>
       <router-link to="/users/12">Users</router-link>
       <hr>
       <app-message></app-message>
       <hr>
       <router-view></router-view>
  </div>
</template>

<script>
export default {
  name: 'app',
  data () {
    return {

    }
  }
}
</script>

<style>

</style>
