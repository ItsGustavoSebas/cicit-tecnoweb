<template>
  <div class="bg-white p-8 min-h-screen">
    <h2 class="text-3xl font-bold text-blue-800 mb-6">Cursos CICIT</h2>

    <!-- Formulario de creación -->
    <form autocomplete="on" @submit.prevent="crearCurso" class="grid md:grid-cols-2 gap-4 mb-10">
      <input name="foto" v-model="form.foto" placeholder="URL de la imagen" class="border p-2 rounded" />
      <input name="nombre" v-model="form.nombre" placeholder="Nombre del curso" class="border p-2 rounded" />
      <input name="duracion" v-model="form.duracion" placeholder="Duración (ej. 40 horas)" class="border p-2 rounded" />
      <input name="precio" v-model="form.precio" type="number" placeholder="Precio" class="border p-2 rounded" />
      <input name="instructor" v-model="form.instructor" placeholder="Instructor" class="border p-2 rounded" />


      <div>
        <label class="block font-semibold">Horarios (puedes agregar varios):</label>
        <div v-for="(h, index) in form.horarios" :key="index" class="flex space-x-2 mt-2">
          <select v-model="h.dia" class="border p-1 rounded w-1/2">
            <option disabled value="">Seleccione un día</option>
            <option>Lunes</option>
            <option>Martes</option>
            <option>Miércoles</option>
            <option>Jueves</option>
            <option>Viernes</option>
            <option>Sábado</option>
            <option>Domingo</option>
          </select>
          <input v-model="h.hora" placeholder="Hora" class="border p-1 rounded w-1/2" />
          <button type="button" @click="form.horarios.splice(index, 1)" class="text-red-500 font-bold">X</button>
        </div>
        <button type="button" @click="form.horarios.push({ dia: '', hora: '' })" class="mt-2 text-blue-600 underline">+ Añadir horario</button>
      </div>

      <button type="submit" class="card-button">Guardar curso</button>
    </form>

    <!-- Lista de cursos -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="curso in cursos" :key="curso.id" class="card">
        <h3 class="card-tag">Curso</h3>
        <div class="card-image-wrapper">
          <img width="300" height="200" class="card-image" :src="curso.foto || 'https://via.placeholder.com/300x200'" alt="Imagen del curso" />
          <p class="card-badge">Bs. {{ curso.precio }}</p>
        </div>
        <h1 class="card-title">{{ curso.nombre }}</h1>
        <div class="card-meta">
          <div class="meta-item">📅 {{ curso.duracion }}</div>
          <div class="meta-item">👨‍🏫 {{ curso.instructor }}</div>
          <div class="meta-item" v-for="(h, i) in curso.horarios" :key="i">⏰ {{ h.dia }} - {{ h.hora }}</div>
          <button class="card-button" @click="eliminarCurso(curso.id)">Eliminar</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
  import { ref, onMounted } from 'vue'
  import axios from 'axios'

  const cursos = ref([])
  const form = ref({
    foto: '',
    nombre: '',
    duracion: '',
    horarios: [{ dia: '', hora: '' }],
    precio: '',
    instructor: ''
  })

  const cargarCursos = async () => {
    const res = await axios.get('/api/get-cursos')
    cursos.value = res.data
  }

  const crearCurso = async () => {
    await axios.post('/api/create-curso', form.value)
    form.value = {
      foto: '',
      nombre: '',
      duracion: '',
      horarios: [{ dia: '', hora: '' }],
      precio: '',
      instructor: ''
    }
    await cargarCursos()
  }

  const eliminarCurso = async (id) => {
    await axios.delete(`/api/delete-curso/${id}`)
    await cargarCursos()
  }

  onMounted(() => {
    cargarCursos()
  });
</script>

<style>
  .card {
      max-width: 24rem;
      background-color: white;
      padding: 1.5rem 1.5rem 0.5rem;
      border-radius: 1rem;
      box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
      transition: transform 0.5s;
  }
  .card:hover {
      transform: scale(1.05);
  }

  .card-tag {
      margin-bottom: 0.75rem;
      font-size: 1.25rem;
      font-weight: bold;
      color: #4f46e5;
  }

  .card-image-wrapper {
      position: relative;
  }

  .card-image {
      width: 100%;
      border-radius: 1rem;
      display: block;
  }

  .card-badge {
      position: absolute;
      top: 0;
      left: 0;
      background-color: #fcd34d; 
      color: #1f2937;
      font-weight: 600;
      padding: 0.25rem 0.75rem;
      border-top-left-radius: 0.5rem;
      border-bottom-right-radius: 0.5rem;
  }

  .card-title {
      margin-top: 1rem;
      font-size: 1.5rem;
      font-weight: bold;
      color: #1f2937;
      cursor: pointer;
  }

  .card-meta {
      margin-top: 1rem;
      margin-bottom: 1rem;
  }

  .meta-item {
      display: flex;
      align-items: center;
      gap: 0.25rem;
      margin-bottom: 0.5rem;
      color: #374151;
  }

  .icon {
      font-size: 1.25rem;
      color: #4f46e5;
  }

  .card-button {
      margin-top: 1rem;
      font-size: 1.25rem;
      width: 100%;
      padding: 0.5rem 0;
      background-color: #4f46e5;
      color: white;
      border-radius: 1rem;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      border: none;
      cursor: pointer;
  }
  .card-button:hover {
      background-color: #4338ca;
  }
</style>
