<template>
  <AuthenticatedLayout>
    <h2>Factura #{{ factura.codigo }}</h2>
    <a
    :href="route('facturas.pdf', factura.codigo)"
    target="_blank"
    class="btn btn-outline-secondary mt-3"
  >
    Descargar PDF
  </a>

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

    <Link :href="route('facturas.index')" class="btn btn-secondary mt-3">
      Volver a facturas
    </Link>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Link, usePage } from '@inertiajs/vue3'

const { factura } = usePage().props
</script>
