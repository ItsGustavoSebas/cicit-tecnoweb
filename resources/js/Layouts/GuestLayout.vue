<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import HeaderGuest from '@/Components/HeaderGuest.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div id="app-2" class="app-2 default">
        <HeaderGuest/>
    <div  id="app-wrapper"
    class="flex min-h-screen flex-col items-center pt-6 sm:justify-center sm:pt-0 bg-light"
    >

    <div class="mt-6 overflow-hidden shadow-md sm:rounded-lg" id="app-body">
    <slot />
  </div>
    </div>
    </div>
</template>
