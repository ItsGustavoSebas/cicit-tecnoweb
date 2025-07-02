<template>
  <AuthenticatedLayout>
  <div class="d-lg-flex d-block justify-content-between align-items-center mb-3">
      <span class="fs-4">Factura #{{ factura.codigo }}</span>
  
      <a :href="route('facturas.pdf', factura.codigo)" class="btn btn-primary btn-sm" target="_blank">
        <i class="bi bi-download me-1"></i> Descargar PDF
      </a>
    </div>

    <p><strong>Usuario:</strong> {{ factura.usuario }}</p>
    <p><strong>Fecha:</strong> {{ factura.created_at }}</p>
    <p><strong>Total:</strong> {{ factura.monto }}</p>

    <h5>Detalle:</h5>
    <table class="table">
      <thead><tr><th>Estudiante</th><th>Curso</th><th>Monto</th></tr></thead>
      <tbody>
        <tr v-for="it in factura.items" :key="it.curso+it.estudiante">
          <td>{{ it.estudiante }}</td>
          <td>{{ it.curso }}</td>
          <td>{{ it.monto }}</td>
        </tr>
      </tbody>
    </table>

    <Link :href="route('facturas.index')" class="btn btn-primary btn-sm mt-3">
      Volver a facturas
    </Link>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Link, usePage } from '@inertiajs/vue3'

const { factura } = usePage().props
</script>
