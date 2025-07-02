<template>
  <AuthenticatedLayout>
    <h2>Funcionalidades para {{ role.nombre }}</h2>

    <table class="table table-hover table-bordered">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Ruta</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="f in funcionalidades"
          :key="f.codigo"
          @click="selectFuncionalidad(f)"
          style="cursor: pointer"
        >
          <td>{{ f.nombre }}</td>
          <td>/{{ f.ruta }}</td>
          <td>
            <span :class="f.asignado ? 'text-success' : 'text-danger'">
              {{ f.asignado ? 'Asignado' : 'No asignado' }}
            </span>
          </td>
        </tr>
      </tbody>
    </table>

    <div class="mt-4">
      <button @click="irAListaRoles" class="btn btn-secondary">
        Guardar y volver a la lista de roles
      </button>
    </div>

    <!-- Modal: NO usar v-if aquí, que elimina el <dialog> -->
    <Modal :show="!!selectedFuncionalidad" @close="selectedFuncionalidad = null">
  <template #default>
    <div class="p-4">
      <h3 class="text-lg font-semibold mb-4">
        Permisos para {{ selectedFuncionalidad?.nombre }}
      </h3>

      <div v-for="r in recursos" :key="r.codigo" class="form-check mb-2">
        <input
          type="checkbox"
          class="form-check-input"
          :value="r.codigo"
          v-model="recursosSeleccionados"
          :id="'check-' + r.codigo"
        />
        <label class="form-check-label ms-1" :for="'check-' + r.codigo">
          {{ r.descripcion }}
        </label>
      </div>

      <div class="mt-4 flex justify-between">
        <button class="btn btn-secondary" @click="selectedFuncionalidad = null">
          Cancelar
        </button>
        <button class="btn btn-primary" @click="guardar">
          Guardar
        </button>
      </div>
    </div>
  </template>
</Modal>


  </AuthenticatedLayout>
</template>

<script setup>
import { ref, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { Inertia } from '@inertiajs/inertia'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Modal from '@/Components/Modal.vue'

const { role, funcionalidades, recursos } = usePage().props
console.log(funcionalidades);
const selectedFuncionalidad = ref(null)
const recursosSeleccionados = ref([])

function selectFuncionalidad(f) {
  selectedFuncionalidad.value = f
  recursosSeleccionados.value = [...f.recursos_asignados]
  console.log(recursosSeleccionados.value);
}

function irAListaRoles() {
  Inertia.visit(route('roles.index'))
}



function guardar() {
  Inertia.put(
    route('roles.functionalities.update', role.codigo),
    {
      funcionalidad_id: selectedFuncionalidad.value.codigo,
      recursos: recursosSeleccionados.value
    },
    {
      onSuccess: () => {
        selectedFuncionalidad.value = null
      }
    }
  )
}

</script>
