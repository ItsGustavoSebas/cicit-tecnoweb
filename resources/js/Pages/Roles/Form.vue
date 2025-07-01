

<template>
    <AuthenticatedLayout>
  <h2>{{ mode === 'create' ? 'Nuevo rol' : 'Editar rol' }}</h2>
    <form @submit.prevent="submit">
      <div class="mb-3">
        <label class="form-label">Nombre *</label>
        <input
          v-model="form.nombre"
          type="text"
          class="form-control"
          :class="{ 'is-invalid': errors.nombre }"
        />
        <div class="invalid-feedback">{{ errors.nombre }}</div>
      </div>

      <div class="mb-3">
        <label class="form-label">Descripción</label>
        <textarea v-model="form.descripcion" class="form-control"></textarea>
      </div>

      <button class="btn btn-primary">
        Guardar
      </button>
    </form>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { reactive, toRefs } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { usePage } from '@inertiajs/vue3'

const props = usePage().props
const mode = props.mode            // 'create' o 'edit'
const role = props.role || {}      // si es edit

const form = reactive({
  nombre: role.nombre || '',
  descripcion: role.descripcion || '',
})

const errors = props.errors || {}

function submit() {
  const url   = mode === 'create'
    ? route('roles.store')
    : route('roles.update', role.codigo)
  const method = mode === 'create' ? 'post' : 'put'

  Inertia[method](url, form, {
    onError: () => window.scrollTo(0,0)
  })
}
</script>
