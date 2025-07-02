<template>
    <AuthenticatedLayout>
      <!-- contenedor principal -->
      <div class="mt-4">
        <div class="col-12">
          <!-- título + botón volver -->
          <div class="d-lg-flex d-block justify-content-between align-items-center">
            <span class="fs-4">Estudiantes inscritos en {{ curso.nombre }}</span>
            <Link :href="route('cursos.index')"
                  class="btn btn-primary btn-sm d-flex align-items-center">
              <i class="bi bi-arrow-left me-1"></i> Volver
            </Link>
            <a :href="route('cursos.inscripciones.export', curso.codigo)"
              class="btn btn-primary btn-sm d-flex align-items-center"
              target="_blank">
              <i class="bi bi-download me-1"></i> Exportar CSV
            </a>
          </div>
  
          <hr />
  
          <!-- tarjeta con tabla de estudiantes -->
          <div class="card" wire:ignore.self>
            <div class="card-body" style="overflow-x: auto">
              <table class="table table-hover table-bordered" style="white-space: nowrap" wire:ignore.self>
                <thead class="table-primary">
                  <tr>
                    <th scope="col" class="text-success">Nombre</th>
                    <th scope="col" class="text-success">Apellido</th>
                    <th scope="col" class="text-success">CI</th>
                    <th scope="col" class="text-success">Monto</th>
                    <th scope="col" class="text-success">Estado</th>
                    <th scope="col" class="text-success">Fecha Inscripción</th>
                    <th scope="col" class="text-success"></th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="i in inscripciones" :key="i.codigo">
                    <td>{{ i.estudiante.nombre }}</td>
                    <td>{{ i.estudiante.apellido }}</td>
                    <td>{{ i.estudiante.ci }}</td>
                    <td>{{ i.monto }}</td>
                    <td>{{ i.estado?.nombre ?? i.estado_id }}</td> 
                    <td>{{ new Date(i.created_at).toLocaleDateString() }}</td>
                    <td class="d-flex gap-2">
                      <a v-if="i.estado_id === 3"
                        :href="route('inscripciones.certificado', i.codigo)"
                        class="btn btn-outline-success btn-sm"
                        target="_blank"
                        title="Certificado">
                        <i class="bi bi-file-earmark-check"></i>
                      </a>

                      <!-- Formulario -->
                      <a :href="route('inscripciones.formulario', i.codigo)"
                        class="btn btn-outline-primary btn-sm"
                        target="_blank"
                        title="Formulario">
                        <i class="bi bi-printer"></i>
                      </a>

                    </td>
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
  