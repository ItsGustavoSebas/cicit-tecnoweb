<template>
    <AuthenticatedLayout>
      <div class="max-w-xl mx-auto card  p-6 shadow rounded-lg">
        <h2 class="text-lg font-bold mb-4">Editar</h2>
        <CourseForm
          :curso="curso"
          :profesores="profesores"
          :cronos="cronos"
          :precios-previos="preciosPrevios" 
          :tipos-estudiante="tiposEstudiante"
          :action="route('cursos.update', curso.codigo)"
          method="put"
        />
      </div>
    </AuthenticatedLayout>
  </template>
  
<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import CourseForm from './_Form.vue';
    import { usePage } from '@inertiajs/vue3';

    const { curso, profesores, cronos, preciosPrevios, tiposEstudiante } = usePage().props;
</script>
  