<template>
  <div class="app__body__footer">
    <span class="me-4">
      &copy; {{ new Date().getFullYear() }} {{ appName }} Todos los derechos reservados.
    </span>
    <span class="app__body__footer__visitas">
      {{ visitas }}
    </span>
  </div>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3'

const { props } = usePage()
const visitas = props.visitas
const appName = import.meta.env.VITE_APP_NAME || 'Laravel'
</script>
