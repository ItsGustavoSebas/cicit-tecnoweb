<template>

    <AuthenticatedLayout>
    <template>
      <h2>Roles</h2>
      <Link :href="route('roles.create')" class="btn btn-primary">Nuevo rol</Link>
    </template>
    <div class="card" wire:ignore.self>
    <div class="card-body" style="overflow-x: auto">
    <table class="table table-hover table-bordered" style="white-space: nowrap" wire:ignore.self>
      <thead class="table-primary">
        <tr>
            <th scope="col" class="text-success">Nombre</th>
            <th scope="col" class="text-success">Descripción</th>
            <th scope="col" class="text-success"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="role in roles" :key="role.codigo">
          <td>{{ role.nombre }}</td>
          <td>{{ role.descripcion || '-' }}</td>
          <td class="d-flex gap-2">
            <Link :href="route('roles.edit', role.codigo)" class="btn btn-outline-secondary btn-sm">
              <i class="bi bi-pencil-square"></i>
            </Link>
            <Link
              :href="route('roles.functionalities.edit', role.codigo)"
              class="btn btn-outline-success btn-sm"
            >
              <i class="bi bi-plus-square-fill"></i>
            </Link>
            <button
              @click="destroy(role.codigo)"
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
import { Inertia } from '@inertiajs/inertia'
import { Link, usePage } from '@inertiajs/vue3'

const { roles } = usePage().props

function destroy(id) {
  if (confirm('¿Eliminar este rol?')) {
    Inertia.delete(route('roles.destroy', id))
  }
}
</script>
