<script setup>
import { onMounted } from 'vue';
import { useAuthStore } from './store/auth';
const authStore = useAuthStore();
onMounted(async () => {
    const isReload = performance.navigation.type === 1;
    if (isReload) {
      await authStore.getCurrentUser()
    }
});

</script>

<template>
  <div id="app">
    <router-view></router-view>
  </div>
</template>

<style scoped>

</style>
