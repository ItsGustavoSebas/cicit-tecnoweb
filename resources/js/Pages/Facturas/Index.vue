<template>
  <AuthenticatedLayout>
    <h2>Facturas</h2>
    <Link :href="route('facturas.create')" class="btn btn-primary mb-3">
      Nueva factura
    </Link>

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
