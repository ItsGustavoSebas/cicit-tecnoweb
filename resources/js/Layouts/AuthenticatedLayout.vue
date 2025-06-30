<script setup>
import { ref } from 'vue';
import Sidenav from '@/Components/Sidenav.vue';
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="app default">
    <Sidenav/>
    <Header/>

    <main class="app__body" id="app-body">
        <slot />
      <Footer />
    </main>
  </div>
</template>
z