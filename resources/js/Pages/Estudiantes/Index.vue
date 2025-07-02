<template>
  <AuthenticatedLayout>
    <div class="d-lg-flex d-block justify-content-between align-items-center">
      <span class="fs-4">Estudiantes</span>
  
      <!-- botón +Nuevo -->
      <Link :href="route('estudiantes.create')"
            class="btn btn-primary btn-sm d-flex align-items-center">
        <i class="bi bi-plus-lg me-1"></i> Nuevo
      </Link>
      <a :href="route('estudiantes.export')" class="btn btn-primary btn-sm" target="_blank">
        <i class="bi bi-download me-1"></i> Exportar CSV
      </a>
    </div>

    <div class="card">
      <div class="card-body" style="overflow-x:auto">
        <table class="table table-hover table-bordered" style="white-space:nowrap">
          <thead class="table-primary">
            <tr>
              <th>Nombre</th>
              <th>Apellido</th>
              <th>CI</th>
              <th>Tipo</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="e in estudiantes" :key="e.codigo">
              <td>{{ e.nombre }}</td>
              <td>{{ e.apellido }}</td>
              <td>{{ e.ci }}</td>
              <td>{{ e.tipo_nombre || '-' }}</td>
              <td class="d-flex gap-2">
                <Link
                  :href="route('estudiantes.edit', e.codigo)"
                  class="btn btn-outline-secondary btn-sm"
                >
                  <i class="bi bi-pencil-square"></i>
                </Link>
                <button
                  @click="destroy(e.codigo)"
                  class="btn btn-outline-danger btn-sm"
                >
                  <i class="bi bi-trash"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Inertia } from '@inertiajs/inertia'
import { Link, usePage } from '@inertiajs/vue3'

const { estudiantes } = usePage().props

function destroy(id) {
  if (confirm('¿Eliminar este estudiante?')) {
    Inertia.delete(route('estudiantes.destroy', id))
  }
}
</script>
