<template>
  <div>
    <Auth />

    <hr />

    <router-view />
  </div>
</template>

<script setup>
import Auth from './components/Auth.vue'
</script>