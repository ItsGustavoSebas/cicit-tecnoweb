<template>
  <AuthenticatedLayout>
    <h2>
      {{ mode==='create'? 'Nuevo usuario' : 'Editar usuario' }}
    </h2>

    <form @submit.prevent="submit">
      <div class="mb-3">
        <label class="form-label">Nombre *</label>
        <input
          v-model="form.nombre"
          type="text"
          class="form-control"
          :class="{ 'is-invalid': errors.nombre }"
        />
        <div class="invalid-feedback">{{ errors.nombre }}</div>
      </div>

      <div class="mb-3">
        <label class="form-label">CI *</label>
        <input
          v-model="form.ci"
          type="text"
          class="form-control"
          :class="{ 'is-invalid': errors.ci }"
        />
        <div class="invalid-feedback">{{ errors.ci }}</div>
      </div>

      <div class="mb-3">
        <label class="form-label">Email *</label>
        <input
          v-model="form.email"
          type="email"
          class="form-control"
          :class="{ 'is-invalid': errors.email }"
        />
        <div class="invalid-feedback">{{ errors.email }}</div>
      </div>

      <div class="mb-3">
        <label class="form-label">Rol *</label>
        <select
          v-model="form.rol_id"
          class="form-select"
          :class="{ 'is-invalid': errors.rol_id }"
        >
          <option value="">— Seleccionar —</option>
          <option v-for="r in roles" :key="r.codigo" :value="r.codigo">
            {{ r.nombre }}
          </option>
        </select>
        <div class="invalid-feedback">{{ errors.rol_id }}</div>
      </div>

      <div class="mb-3" v-if="mode==='create'">
        <label class="form-label">Contraseña *</label>
        <input
          v-model="form.password"
          type="password"
          class="form-control"
          :class="{ 'is-invalid': errors.password }"
        />
        <div class="invalid-feedback">{{ errors.password }}</div>
      </div>

      <div class="mb-3" v-if="mode==='create'">
        <label class="form-label">Confirmar contraseña *</label>
        <input
          v-model="form.password_confirmation"
          type="password"
          class="form-control"
          :class="{ 'is-invalid': errors.password_confirmation }"
        />
      </div>

      <button class="btn btn-primary">
        Guardar
      </button>
    </form>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { reactive } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';

const props = usePage().props;
const mode = props.mode;
const user = props.user || {};

const roles = props.roles || [];

const form = reactive({
  nombre: user.nombre || '',
  ci:     user.ci || '',
  email:  user.email || '',
  rol_id: user.rol_id || '',
  password: '',
  password_confirmation: '',
});

const errors = props.errors || {};

function submit() {
  const url    = mode==='create'
    ? route('users.store')
    : route('users.update', user.codigo);
  const method = mode==='create' ? 'post' : 'put';

  Inertia[method](url, form, {
    onError: () => window.scrollTo(0,0),
  });
}
</script>
