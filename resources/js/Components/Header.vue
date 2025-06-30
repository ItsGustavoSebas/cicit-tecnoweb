<template>
  <header class="app__header">
    <div class="app__header__left">
      <FuncionalidadSearch />
    </div>
    <div class="app__header__right">
      <ThemeSelector />
      <DarkModeSelector />

      <form :action="route('logout')" method="POST">

        <input type="hidden" name="_token" :value="csrf" />
        <button
          data-bs-toggle="tooltip"
          data-bs-placement="left"
          title="Salir"
          type="submit"
          class="btn btn-outline-secondary me-2 d-flex justify-content-center align-items-center"
          style="width: 30px; height: 30px"
        >
          <i class="bi bi-box-arrow-in-left fs-4" style="position: relative; top: 2px; left: 2px"></i>
        </button>
      </form>
    </div>
  </header>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3'
import FuncionalidadSearch from '@/Components/FuncionalidadSearch.vue'
import ThemeSelector from '@/Components/ThemeSelector.vue'
import DarkModeSelector from '@/Components/DarkModeSelector.vue'

const { props } = usePage()
const metaTag = document.querySelector('meta[name="csrf-token"]')
const csrf = props.csrf || (metaTag ? metaTag.getAttribute('content') : '')


import { router } from '@inertiajs/vue3'

const logout = () => {
  router.post(route('logout'))
}

</script>
