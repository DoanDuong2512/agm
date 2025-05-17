<template>
  <div class="layout-container min-h-screen flex flex-col bg-white">
    <Navbar />
    <div class="flex-grow flex flex-col">
      <router-view class="h-full flex-grow"></router-view>
    </div>
    <Footer />
  </div>
</template>

<script>
import Footer from '@/components/Footer.vue';
import Navbar from '@/components/Navbar.vue';

export default {
    components: {
        Footer,
        Navbar
    }
};
</script>

<style>

</style>
