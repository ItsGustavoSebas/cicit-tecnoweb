<template>
  <AuthenticatedLayout>
    <h2>{{ mode==='create' ? 'Nuevo estudiante' : 'Editar estudiante' }}</h2>

    <form @submit.prevent="submit">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Nombre *</label>
          <input
            v-model="form.nombre"
            type="text"
            class="form-control"
            :class="{ 'is-invalid': errors.nombre }"
          />
          <div class="invalid-feedback">{{ errors.nombre }}</div>
        </div>

        <div class="col-md-6">
          <label class="form-label">Apellido *</label>
          <input
            v-model="form.apellido"
            type="text"
            class="form-control"
            :class="{ 'is-invalid': errors.apellido }"
          />
          <div class="invalid-feedback">{{ errors.apellido }}</div>
        </div>

        <div class="col-md-4">
          <label class="form-label">CI *</label>
          <input
            v-model="form.ci"
            type="number"
            class="form-control"
            :class="{ 'is-invalid': errors.ci }"
          />
          <div class="invalid-feedback">{{ errors.ci }}</div>
        </div>

        <div class="col-md-8">
          <label class="form-label">Tipo de Estudiante *</label>
          <select
            v-model="form.tipo_estudiante_id"
            class="form-select"
            :class="{ 'is-invalid': errors.tipo_estudiante_id }"
          >
            <option value="">— Seleccionar —</option>
            <option v-for="t in tipos" :key="t.codigo" :value="t.codigo">
              {{ t.nombre }}
            </option>
          </select>
          <div class="invalid-feedback">{{ errors.tipo_estudiante_id }}</div>
        </div>
      </div>

      <div class="mt-4">
        <button class="btn btn-primary">
          Guardar
        </button>
      </div>
    </form>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { reactive } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { Inertia } from '@inertiajs/inertia'

const props = usePage().props
const mode = props.mode
const estudiante = props.estudiante || {}
const tipos = props.tipos || []

const form = reactive({
  nombre: estudiante.nombre || '',
  apellido: estudiante.apellido || '',
  ci: estudiante.ci || '',
  tipo_estudiante_id: estudiante.tipo_estudiante_id || '',
})

const errors = props.errors || {}

function submit() {
  const url = mode==='create'
    ? route('estudiantes.store')
    : route('estudiantes.update', estudiante.codigo)
  const method = mode==='create' ? 'post' : 'put'

  Inertia[method](url, form, {
    onError: () => window.scrollTo(0,0),
  })
}
</script>
