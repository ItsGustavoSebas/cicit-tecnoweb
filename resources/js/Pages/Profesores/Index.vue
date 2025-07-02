<template>
  <AuthenticatedLayout>
    <div class="d-lg-flex d-block justify-content-between align-items-center">
      <span class="fs-4">Profesores</span>
  
      <!-- botón +Nuevo -->
      <Link :href="route('profesores.create')"
            class="btn btn-primary btn-sm d-flex align-items-center">
        <i class="bi bi-plus-lg me-1"></i> Nuevo
      </Link>
      <a :href="route('profesores.export')" class="btn btn-primary btn-sm" target="_blank">
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
              <th>Título</th>
              <th>CI</th>
              <th>Foto</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="p in profesores" :key="p.codigo">
              <td>{{ p.nombre }}</td>
              <td>{{ p.apellido }}</td>
              <td>{{ p.titulo }}</td>
              <td>{{ p.ci }}</td>
              <td>
                <img
                  v-if="p.foto"
                  :src="`/storage/${p.foto}`"
                  alt="foto"
                  style="height:40px; object-fit:cover"
                />
                <span v-else class="text-muted">—</span>
              </td>
              <td class="d-flex gap-2">
                <Link
                  :href="route('profesores.edit', p.codigo)"
                  class="btn btn-outline-secondary btn-sm"
                >
                  <i class="bi bi-pencil-square"></i>
                </Link>
                <button
                  @click="destroy(p.codigo)"
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
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Inertia } from '@inertiajs/inertia';
import { Link, usePage } from '@inertiajs/vue3';

const { profesores } = usePage().props;

function destroy(id) {
  if (confirm('¿Eliminar este profesor?')) {
    Inertia.delete(route('profesores.destroy', id));
  }
}
</script>
