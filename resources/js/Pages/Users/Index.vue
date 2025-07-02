<template>
  <AuthenticatedLayout>
    <h2>Usuarios</h2>
    <Link :href="route('users.create')" class="btn btn-primary mb-3">
      Nuevo usuario
    </Link>
    <a
        :href="route('users.export')"
        class="btn btn-success mb-3 ml-3"
        target="_blank"
      >
        Exportar CSV
      </a>

    <div class="card">
      <div class="card-body" style="overflow-x:auto">
        <table class="table table-hover table-bordered" style="white-space:nowrap">
          <thead class="table-primary">
            <tr>
              <th>Nombre</th>
              <th>CI</th>
              <th>Email</th>
              <th>Rol</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="u in users" :key="u.codigo">
              <td>{{ u.nombre }}</td>
              <td>{{ u.ci }}</td>
              <td>{{ u.email }}</td>
              <td>{{ u.rol_nombre || '-' }}</td>
              <td class="d-flex gap-2">
                <Link
                  :href="route('users.edit', u.codigo)"
                  class="btn btn-outline-secondary btn-sm"
                >
                  <i class="bi bi-pencil-square"></i>
                </Link>
                <button
                  @click="destroy(u.codigo)"
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

const { users } = usePage().props;

function destroy(id) {
  if (confirm('¿Eliminar este usuario?')) {
    Inertia.delete(route('users.destroy', id));
  }
}
</script>
