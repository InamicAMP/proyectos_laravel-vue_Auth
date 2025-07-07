<script setup>
//import HelloWorld from './components/HelloWorld.vue'
import { RouterView } from 'vue-router';
</script>

<template>

  <!-- <router-link to="/home">Inicio</router-link>
  <router-link to="/register">Registro</router-link>
  <router-link to="/login">Login</router-link> -->
  <router-view/>  <!-- Se van a cargar todas las vistas -->
 <!-- 
  <div>
    <a href="https://vite.dev" target="_blank">
      <img src="/vite.svg" class="logo" alt="Vite logo" />
    </a>
    <a href="https://vuejs.org/" target="_blank">
      <img src="./assets/vue.svg" class="logo vue" alt="Vue logo" />
    </a>
  </div>
  <HelloWorld msg="Vite + Vue" />
-->
</template>

<style scoped>
/* .logo {
  height: 6em;
  padding: 1.5em;
  will-change: filter;
  transition: filter 300ms;
}
.logo:hover {
  filter: drop-shadow(0 0 2em #646cffaa);
}
.logo.vue:hover {
  filter: drop-shadow(0 0 2em #42b883aa);
} */
</style>
