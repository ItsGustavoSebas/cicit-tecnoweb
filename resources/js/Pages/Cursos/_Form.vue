<template>
    <div class="col-12">
      <!-- Tarjeta Bootstrap -->
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">
            {{ props.method === 'post' ? 'Nuevo curso' : 'Editar curso' }}
          </h5>
        </div>
  
        <div class="card-body">
          <!-- Formulario con las clases del modal original -->
          <form id="cursoForm" class="row row-cols-1" @submit.prevent="submit">
  
            <!-- Nombre ---------------------------------------------------- -->
            <div class="mb-3">
              <label class="form-label fw-bold">Nombre *</label>
              <input
                v-model="form.nombre"
                type="text"
                class="form-control"
                :class="{ 'is-invalid': form.errors.nombre }"
                placeholder="Introducción a Laravel"
              />
              <small v-if="form.errors.nombre" class="text-danger">
                {{ form.errors.nombre }}
              </small>
            </div>
  
            <!-- Cupo / Modalidad ----------------------------------------- -->
            <div class="row mb-3 g-3">
              <div class="col-md-6">
                <label class="form-label fw-bold">Cupo *</label>
                <input
                  v-model.number="form.cupo"
                  type="number"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.cupo }"
                />
                <small v-if="form.errors.cupo" class="text-danger">
                  {{ form.errors.cupo }}
                </small>
              </div>
  
              <div class="col-md-6">
                <label class="form-label fw-bold">Modalidad *</label>
                <select v-model="form.presencial" class="form-select">
                  <option :value="true">Presencial</option>
                  <option :value="false">Virtual</option>
                </select>
              </div>
            </div>
  
            <!-- Duración -------------------------------------------------- -->
            <div class="mb-3">
              <label class="form-label fw-bold">Duración</label>
              <input
                v-model="form.duracion"
                type="string"
                class="form-control"
                :class="{ 'is-invalid': form.errors.duracion }"
              />
              <small v-if="form.errors.duracion" class="text-danger">
                {{ form.errors.duracion }}
              </small>
            </div>
  
            <!-- Profesor -------------------------------------------------- -->
            <div class="mb-3">
              <label class="form-label fw-bold">Profesor *</label>
              <select v-model="form.profesor_id" class="form-select">
                <option v-for="p in profesores" :key="p.codigo" :value="p.codigo">
                  {{ p.nombre }}
                </option>
              </select>
              <small v-if="form.errors.profesor_id" class="text-danger">
                {{ form.errors.profesor_id }}
              </small>
            </div>
  
            <!-- Cronograma ------------------------------------------------ -->
            <h6 class="fw-bold mt-4">Cronograma</h6>
  
            <div
              v-for="(c, i) in form.cronogramas"
              :key="i"
              class="row g-3 align-items-end mb-2"
            >
              <div class="col-md-3">
                <label class="form-label">Día</label>
                <input v-model="c.dia" class="form-control" placeholder="Lunes" />
              </div>
              <div class="col-md-3">
                <label class="form-label">Inicio</label>
                <input v-model="c.hora_inicio" type="time" class="form-control" />
              </div>
              <div class="col-md-3">
                <label class="form-label">Fin</label>
                <input v-model="c.hora_fin" type="time" class="form-control" />
              </div>
              <div class="col-md-3 text-end">
                <button
                  type="button"
                  class="btn btn-outline-danger btn-sm"
                  @click="delRow(i)"
                >
                  <i class="bi bi-trash"></i>
                </button>
              </div>
            </div>
  
            <button
              type="button"
              class="btn btn-outline-secondary btn-sm mb-3"
              @click="addRow"
            >
              + Añadir franja
            </button>
  
            <!-- Precios --------------------------------------------------- -->
            <h6 class="fw-bold mt-4">Precios por tipo de estudiante</h6>
  
            <div
              v-for="(p, i) in form.precios"
              :key="i"
              class="row g-3 align-items-end mb-2"
            >
              <div class="col-md-4">
                <label class="form-label">Precio</label>
                <input
                  v-model.number="p.precio"
                  type="number"
                  step="0.01"
                  class="form-control"
                />
              </div>
  
              <div class="col-md-6">
                <label class="form-label">Tipo de estudiante</label>
                <select v-model="p.tipo_estudiante_id" class="form-select">
                  <option v-for="t in tiposEstudiante" :key="t.codigo" :value="t.codigo">
                    {{ t.nombre }}
                  </option>
                </select>
              </div>
  
              <div class="col-md-2 text-end">
                <button
                  type="button"
                  class="btn btn-outline-danger btn-sm"
                  @click="delPrecio(i)"
                >
                  <i class="bi bi-trash"></i>
                </button>
              </div>
            </div>
  
            <button
              type="button"
              class="btn btn-outline-secondary btn-sm mb-3"
              @click="addPrecio"
            >
              + Añadir precio
            </button>
  
            <!-- Botón Guardar -->
            <div class="text-end">
              <button type="submit" class="bt-primary">
                <i class="bi bi-floppy"></i>
                Guardar
                <span v-if="form.processing" class="spinner-border spinner-border-sm"></span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
</template>
<script setup>
  import { useForm } from "@inertiajs/vue3";
  import { ref } from "vue";

  const props = defineProps({
      curso: Object,
      profesores: Array,
      tiposEstudiante: Array,
      cronos: { type: Array, default: () => [] },
      preciosPrevios: { type: Array, default: () => [] },
      action: String,
      method: { type: String, default: "post" },
  });

  const form = useForm({
      /* datos del curso */
      nombre: props.curso?.nombre ?? "",
      duracion: props.curso?.duracion ?? "",
      cupo: props.curso?.cupo ?? "",
      presencial: props.curso?.presencial ?? true,
      profesor_id: props.curso?.profesor_id ?? "",

      cronogramas: props.cronos?.length
          ? [...props.cronos]
          : [{ dia: "", hora_inicio: "", hora_fin: "" }],

      precios: props.preciosPrevios?.length
          ? [...props.preciosPrevios]
          : [{ precio: "", tipo_estudiante_id: "" }],
  });

  function addRow() {
      form.cronogramas.push({ dia: "", hora_inicio: "", hora_fin: "" });
  }
  function delRow(i) {
      form.cronogramas.splice(i, 1);
  }

  function addPrecio() {
      form.precios.push({ precio: "", tipo_estudiante_id: "" });
  }
  function delPrecio(i) {
      form.precios.splice(i, 1);
  }

  function submit() {
      form.submit(props.method, props.action);
  }
</script>

  