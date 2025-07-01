<template>
    <AuthenticatedLayout>
      <div class=" max-w-xl mx-auto card p-6 shadow rounded-lg">
        <h2 class="text-lg font-bold mb-4">Crear</h2>
        <CourseForm
          :profesores="profesores"
          :tipos-estudiante="tiposEstudiante"
          :action="route('cursos.store')"
          method="post"
        />
      </div>
    </AuthenticatedLayout>
  </template>
  
  <script setup>
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import CourseForm from './_Form.vue';
  import { usePage } from '@inertiajs/vue3';
  
  const { profesores, tiposEstudiante } = usePage().props;
  </script>
  