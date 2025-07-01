<style>
  .descripcion{
    font-size: 16px;
    margin-bottom: 1px;
  }
</style>
<template>
  <GuestLayout>
    <div class="modal-content p-8 min-h-screen">
      <h2 class="text-3xl font-bold text-blue-800 mb-6">Cursos CICIT</h2>



      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="curso in cursos" :key="curso.id" class="card">
          <h3 class="card-tag">Curso</h3>
          <div class="card-image-wrapper">
            <img width="300" height="200" class="card-image" :src="curso.foto || 'https://web-assets.esetstatic.com/wls/2018/04/cursos-online-gratuitos-seguridad-inform%C3%A1tica.jpg'" alt="Imagen del curso" />
            <p class="card-badge"> Bs. {{ curso.precios?.[0]?.precio ?? '—' }}</p>
          </div>
          <h1 style="font-size: 30px;margin-top: 25px;">{{ curso.nombre }}</h1>
          <div class="card-meta">
            <div class="descripcion">📅Duracion: {{ curso.duracion }}</div>
            <div class="descripcion">👨‍🏫Docente: {{ curso.profesor?.nombre ?? '—' }} {{ curso.profesor?.apellido ?? '—' }}</div>
            <div class="descripcion">
              {{ curso.presencial ? '🏫Modalidad Presencial' : '💻Modalidad: Virtual' }}
            </div>

            <div v-if="curso.cronogramas?.length">
              <div class="descripcion">🗓️Cronograma:</div>
              <ul class="list-disc pl-5 text-sm">

                <li v-for="(c, i) in curso.cronogramas" :key="i">
                  {{ c.dia }} — {{ c.hora_inicio }} a {{ c.hora_fin }}
                </li>
              </ul>
            </div>
            <a :href="`/cursos/${curso.codigo}/inscripcion`" class="card-button mt-3 mx-auto block text-center" style="color:white">

              Inscribirse
            </a>

          </div>
        </div>
      </div>
    </div>
  </GuestLayout> 
</template>

<script setup>
  import { ref, onMounted } from 'vue'
  import axios from 'axios'
  import GuestLayout from '@/Layouts/GuestLayout.vue';


  const cursos = ref([])

  const cargarCursos = async () => {
    const res = await axios.get('/api/get-cursos')
    cursos.value = res.data
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
