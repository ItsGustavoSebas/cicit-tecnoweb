<template>
  <AuthenticatedLayout>
    <h2 v-if="step===1">Seleccionar estudiante</h2>
    <h2 v-else>Generar factura para {{ estudiante.nombre }}</h2>

    <div v-if="step===1">
      <select v-model="selected" class="form-select mb-3">
        <option value="">— Elige un estudiante —</option>
        <option v-for="s in students" :key="s.codigo" :value="s.codigo">
          {{ s.nombre }}
        </option>
      </select>
      <button :disabled="!selected" @click="goStep2" class="btn btn-primary">
        Continuar
      </button>
    </div>

    <div v-else>
      <table class="table mb-3">
        <thead>
          <tr><th>Curso</th><th>Monto</th><th></th></tr>
        </thead>
        <tbody>
          <tr v-for="it in items" :key="it.codigo">
            <td>{{ it.curso }}</td>
            <td>{{ it.monto }}</td>
            <td>
              <input type="checkbox" :value="it.codigo" v-model="selectedItems">
            </td>
          </tr>
        </tbody>
      </table>
      <p><strong>Total:</strong> {{ total }}</p>
      <button :disabled="!selectedItems.length" @click="store" class="btn btn-success">
        Generar factura
      </button>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { Inertia } from '@inertiajs/inertia'

const { step, students, estudiante, items } = usePage().props

const selected = ref('')
const selectedItems = ref([])

const total = computed(() => {
  return items
    .filter(i => selectedItems.value.includes(i.codigo))
    .reduce((sum, i) => sum + Number(i.monto), 0)
})


function goStep2() {
  Inertia.visit(route('facturas.create', { estudiante: selected.value }))
}

function store() {
  Inertia.post(route('facturas.store'), {
    estudiante_id: estudiante.codigo,
    items: selectedItems.value
  })
}
</script>
