<template>
  <AuthenticatedLayout>
    <div class="d-lg-flex d-block justify-content-between align-items-center">
      <span class="fs-4">Facturas</span>
  
      <!-- botón +Nuevo -->
      <Link :href="route('facturas.create')"
            class="btn btn-primary btn-sm d-flex align-items-center">
        <i class="bi bi-plus-lg me-1"></i> Nuevo
      </Link>
      <a :href="route('facturas.export')" class="btn btn-primary btn-sm" target="_blank">
        <i class="bi bi-download me-1"></i> Exportar CSV
      </a>
    </div>

    <div class="card">
      <div class="card-body" style="overflow-x:auto">
        <table class="table table-hover table-bordered">
          <thead class="table-primary">
            <tr>
              <th>Código</th>
              <th>Usuario</th>
              <th>Monto</th>
              <th>Fecha</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="f in facturas" :key="f.codigo">
              <td>{{ f.codigo }}</td>
              <td>{{ f.usuario }}</td>
              <td>{{ f.monto }}</td>
              <td>{{ f.created_at }}</td>
              <td>
                <Link :href="route('facturas.show', f.codigo)" class="btn btn-outline-info btn-sm">
                  Ver
                </Link>
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
import { Link, usePage } from '@inertiajs/vue3'
import { Inertia } from '@inertiajs/inertia'

const { facturas } = usePage().props
</script>
