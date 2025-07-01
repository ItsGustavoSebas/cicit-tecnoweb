<template>
  <AuthenticatedLayout>
    <h2>{{ mode==='create'? 'Nuevo profesor' : 'Editar profesor' }}</h2>

    <form @submit.prevent="submit" enctype="multipart/form-data">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Nombre *</label>
          <input
            v-model="form.nombre"
            type="text"
            class="form-control"
            :class="{ 'is-invalid': errors.nombre }"
          />
          <div class="invalid-feedback">{{ errors.nombre }}</div>
        </div>

        <div class="col-md-6">
          <label class="form-label">Apellido *</label>
          <input
            v-model="form.apellido"
            type="text"
            class="form-control"
            :class="{ 'is-invalid': errors.apellido }"
          />
          <div class="invalid-feedback">{{ errors.apellido }}</div>
        </div>

        <div class="col-md-6">
          <label class="form-label">Título *</label>
          <input
            v-model="form.titulo"
            type="text"
            class="form-control"
            :class="{ 'is-invalid': errors.titulo }"
          />
          <div class="invalid-feedback">{{ errors.titulo }}</div>
        </div>

        <div class="col-md-6">
          <label class="form-label">CI *</label>
          <input
            v-model="form.ci"
            type="number"
            class="form-control"
            :class="{ 'is-invalid': errors.ci }"
          />
          <div class="invalid-feedback">{{ errors.ci }}</div>
        </div>

        <div class="col-md-12">
          <label class="form-label">Foto {{ mode==='create'? '*' : '' }}</label>
          <input
            ref="fileInput"
            type="file"
            accept="image/*"
            class="form-control"
            :class="{ 'is-invalid': errors.foto }"
            @change="e => form.foto = e.target.files[0]"
          />
          <div class="invalid-feedback">{{ errors.foto }}</div>
          <div v-if="mode==='edit' && form.fotoPreview" class="mt-2">
            <img :src="form.fotoPreview" alt="actual" style="height:60px;"/>
          </div>
        </div>
      </div>

      <div class="mt-4">
        <button class="btn btn-primary">
          Guardar
        </button>
      </div>
    </form>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { reactive, ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';

const props = usePage().props
console.log('PROPS:', props)          // <<-- esto te mostrará mode, profesor, errors...
const mode = props.mode
const profesor = props.profesor || {}
console.log('PROFESOR:', profesor)    // <<-- y aquí sólo el objeto profesor


const form = reactive({
  nombre: profesor.nombre || '',
  apellido: profesor.apellido || '',
  titulo: profesor.titulo || '',
  ci: profesor.ci || '',
  foto: null,
  fotoPreview: profesor.foto ? `/storage/${profesor.foto}` : ''
});

const errors = props.errors || {};

watch(
  () => profesor.foto,
  () => {
    form.fotoPreview = profesor.foto ? `/storage/${profesor.foto}` : '';
  }
);

function submit() {
  const data = new FormData();
  data.append('nombre', form.nombre);
  data.append('apellido', form.apellido);
  data.append('titulo', form.titulo);
  data.append('ci', form.ci);
  if (form.foto instanceof File) {
    data.append('foto', form.foto);
  }

  const url = mode==='create'
    ? route('profesores.store')
    : route('profesores.update', profesor.codigo);
  const method = mode==='create' ? 'post' : 'post'; // Inertia .post + _method

  Inertia.post(url, data, {
    _method: mode==='create' ? 'post' : 'put',
    onError: () => window.scrollTo(0,0),
  });
}
</script>
