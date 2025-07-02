<template>
    <AuthenticatedLayout>
      <!-- contenedor principal -->
      <div class="mt-4">
        <div class="col-12">
          <!-- título + botón volver -->
          <div class="d-lg-flex d-block justify-content-between align-items-center">
            <span class="fs-4">Estudiantes inscritos en {{ curso.nombre }}</span>
            <Link :href="route('cursos.index')"
                  class="btn btn-secondary btn-sm d-flex align-items-center">
              <i class="bi bi-arrow-left me-1"></i> Volver
            </Link>
          </div>
  
          <hr />
  
          <!-- tarjeta con tabla de estudiantes -->
          <div class="card" wire:ignore.self>
            <div class="card-body" style="overflow-x: auto">
              <table class="table table-hover table-bordered" style="white-space: nowrap" wire:ignore.self>
                <thead class="table-primary">
                  <tr>
                    <th scope="col" class="text-success">Código Estudiante</th>
                    <th scope="col" class="text-success">Nombre</th>
                    <th scope="col" class="text-success">Apellido</th>
                    <th scope="col" class="text-success">CI</th>
                    <th scope="col" class="text-success">Monto</th>
                    <th scope="col" class="text-success">Fecha Inscripción</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="i in inscripciones" :key="i.codigo">
                    <td>{{ i.estudiante.codigo }}</td>
                    <td>{{ i.estudiante.nombre }}</td>
                    <td>{{ i.estudiante.apellido }}</td>
                    <td>{{ i.estudiante.ci }}</td>
                    <td>{{ i.monto }}</td>
                    <td>{{ new Date(i.created_at).toLocaleDateString() }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>
  
  <script setup>
  import { Link, usePage } from '@inertiajs/vue3';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  
  const { curso, inscripciones } = usePage().props;
  </script>
  