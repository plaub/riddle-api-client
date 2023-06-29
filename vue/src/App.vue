<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router'
import RiddleFloatingButtonVue from './components/RiddleFloatingButton.vue';
</script>

<template>
  <div class="app">
    <header>
      <nav>
        <RouterLink to="/">Home</RouterLink>
        <RouterLink to="/page">Page</RouterLink>
      </nav>
    </header>

    <RouterView />
  </div>

  <RiddleFloatingButtonVue />
</template>

<style scoped></style>
