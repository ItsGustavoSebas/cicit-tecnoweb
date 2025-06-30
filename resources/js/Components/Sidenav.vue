<template>
  <div class="nav">
    <div class="nav__header">
      <img class="nav__header__image text-light" :src="logoUrl" alt="mylogo.png" />
      <span>{{ user.nombre }}</span>
    </div>

    <div class="nav__body">
      <div class="nav__body__items">
        <!-- Home -->
        <Link
          :href="route('dashboard')"
          :class="{ 'nav__body__items__active': isActiveRoute('dashboard') }"
        >
          <i class="bi bi-house-door-fill"></i>
          <span>Home</span>
        </Link>

        <!-- Permisos filtrados -->
        <template v-for="permiso in permisos" :key="permiso.codigo">
          <Link
            v-if="permiso.activo && permiso.funcionalidad"
            :href="route(permiso.funcionalidad.ruta)"
            :class="{ 'nav__body__items__active': isActiveRoute(permiso.funcionalidad.ruta) }"
          >
            <i :class="permiso.funcionalidad.icono"></i>
            <span>{{ permiso.funcionalidad.nombre }}</span>
          </Link>
        </template>
      </div>
    </div>
  </div>
</template>


<script setup>
import { computed } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'

// Datos desde el backend
const page = usePage()
const user = page.props.auth.user
const logoUrl = '/assets/images/logo.jpg'
const { props } = usePage()
// Permisos anidados: role → permisos → funcionalidad
const permisos = props.auth?.permisos || []


console.log('Permisos:', permisos)

const isActiveRoute = (path) => {
  return window.location.pathname.startsWith('/' + path)
}
</script>
