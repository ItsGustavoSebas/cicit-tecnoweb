<template>
    <AuthenticatedLayout>
      <!-- contenedor principal -->
      <div class="mt-4">
        <div class="col-12">
          <!-- título + botón -->
          <div class="d-lg-flex d-block justify-content-between align-items-center">
            <span class="fs-4">Cursos</span>
  
            <!-- botón +Nuevo -->
            <Link :href="route('cursos.crear')"
                  class="btn btn-success btn-sm d-flex align-items-center">
              <i class="bi bi-plus-lg me-1"></i> Nuevo
            </Link>
          </div>
  
          <hr />
  
          <!-- tarjeta con tabla -->
          <div class="card" wire:ignore.self>
            <div class="card-body" style="overflow-x: auto">
                <table class="table table-hover table-bordered" style="white-space: nowrap" wire:ignore.self>
                <thead class="table-primary">
                  <tr>
                    <th scope="col" class="text-success">Código</th>
                    <th scope="col" class="text-success">Nombre</th>
                    <th scope="col" class="text-success">Profesor</th>
                    <th scope="col" class="text-success">Cupo</th>
                    <th scope="col" class="text-success">Duracion</th>
                    <th scope="col" class="text-success">Presencial</th>
                    <th scope="col" class="text-success">Cronograma</th>
                    <th scope="col" class="text-success">Precios</th> 
                    <th scope="col" class="text-success">Creado por</th> 
                    <th scope="col" class="text-success"></th>
                  </tr>
                </thead>
  
                <tbody>
                  <tr v-for="c in cursos.data" :key="c.codigo">
                    <td>{{ c.codigo }}</td>
                    <td>{{ c.nombre }}</td>
                    <td>{{ c.profesor ?? '-' }}</td>
                    <td>{{ c.cupo }}</td>
                    <td>{{ c.duracion }}</td>
                    <td>{{ c.presencial }}</td>
                    <td style="white-space: pre-wrap">{{ c.cronosTexto || '—' }}</td>
                    <td style="white-space: pre-wrap">{{ c.preciosTexto || '—' }}</td>
                    <td>{{ c.creadoPor }}</td>
                    <td class="d-flex">
                        <Link  :href="route('cursos.editar', c.codigo)"
                                class="me-1"
                                style="height: 30px"
                                data-bs-toggle="tooltip"
                                title="Editar curso">
                            <i class="bi bi-pencil-square fs-4 h-100 text-success"
                            style="line-height: 30px;"></i>
                        </Link>

                        <button
                            type="button"
                            class="btn p-0 me-1"
                            style="height:30px"
                            data-bs-toggle="tooltip"
                            title="Eliminar curso"
                            @click.prevent="pedirBorrado(route('cursos.delete', c.codigo))"
                            >
                            <i class="bi bi-trash-fill fs-4 h-100 text-danger" style="line-height:30px;"></i>
                        </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
  
          <!-- paginación -->
          <div class="d-flex justify-content-end mt-2 gap-2">
            <Link v-if="cursos.prev_page_url"
                  :href="cursos.prev_page_url"
                  class="btn btn-outline-secondary btn-sm"
                  preserve-scroll>
              « Anterior
            </Link>
            <Link v-if="cursos.next_page_url"
                  :href="cursos.next_page_url"
                  class="btn btn-outline-secondary btn-sm"
                  preserve-scroll>
              Siguiente »
            </Link>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>
  
<script setup>
    import { Link, router, usePage } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

    const { cursos } = usePage().props;
    function pedirBorrado(url) {
        if (confirm('¿Eliminar definitivamente?')) {
            router.delete(url, { preserveScroll: true });
        }
    }
</script>
  