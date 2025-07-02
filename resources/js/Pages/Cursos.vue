<style>
.app-2.nino .descripcion {
  /* por ejemplo: texto azul claro cuando esté en modo niño */
  color: #0D47A1;
}

.app-2.adulto .descripcion {
  /* gris oscuro cuando esté en modo adulto */
  color: #78868DFF;
  font-weight: bold;
}

/* o cualquier otra regla: */
.app-2.nino .card-button {
  background-color: #0D47A1;
}
.app-2.adulto .card-button {
  background-color: #42585EFF;
  font-weight: bold;
}

.btn {
  @apply inline-flex items-center px-3 py-1.5 rounded transition;
}
.btn-sm   { font-size: .875rem; }
.btn-outline-primary { @apply border border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white; }
.btn-outline-success { @apply border border-green-600 text-green-600 hover:bg-green-600 hover:text-white; }
.btn-secondary       { @apply bg-gray-500 text-white hover:bg-gray-600; }

.app-2 {                                  
  --btn-main-bg:    #43A047;            
  --card-main-bg:   #0A2BC0FF;           
  --card-side-bg:   #3CBE2BFF;            
}

.app-2.nino {
  --btn-main-bg:    #0D47A1;               
  --card-main-bg:   #03A9F4;           
  --card-side-bg:   #171AD6FF;             
}

.app-2.adulto {
  --btn-main-bg:    #42585E;              
  --card-main-bg:   #78909C;                
  --card-side-bg:   #2E2F33FF;              
}

.card-button {
  background-color: var(--btn-main-bg);
  color: #fff;
  transition: background .2s;
}
.card-button:hover {
  filter: brightness(.9);
}

.info-card {                     
  color: #fff;
  border-radius: .75rem;
  padding: 1.5rem;
}
.info-card.primary   { background: var(--card-main-bg);  }
.info-card.secondary { background: var(--card-side-bg);  }
</style>
<template>
  <GuestLayout>
<div class="grid md:grid-cols-2 gap-6 mb-8">

  <!-- Tarjeta principal -->
  <div class="info-card primary">
    <h3 class="text-xl font-bold mb-2">
      ¿Necesita reimprimir su formulario de preinscripción o inscripción?
    </h3>
    <p class="mb-4">Haga click en la siguiente opción.</p>

    <button @click="showModal = true" class="card-button w-auto btn btn-primary">
      Imprimir formulario
    </button>
  </div>

  <!-- Tarjeta secundaria -->
  <div class="info-card secondary">
    <h3 class="text-xl font-bold mb-2">
      ¿Ya tiene un formulario pero desea corregir un dato erróneo?
    </h3>
    <p>
      Apersónese a oficinas de la CICIT en el módulo 236
    </p>
  </div>

</div>


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
            <div class="descripcion">📅Cupos: {{ curso.cupo }}</div>

            <div v-if="curso.cronogramas?.length">
              <div class="descripcion">🗓️Cronograma:</div>
              <ul class="list-disc pl-5 text-sm">

                <li v-for="(c, i) in curso.cronogramas" :key="i">
                  {{ c.dia }} — {{ c.hora_inicio }} a {{ c.hora_fin }}
                </li>
              </ul>
            </div>
            <a :href="`/cursos/${curso.codigo}/inscripcion`" class="card-button mt-3 mx-auto block text-center btn btn-primary" style="color:white">

              Inscribirse
            </a>

          </div>
        </div>
      </div>
    </div>
    <!-- MODAL -->
<div v-if="showModal"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
  <div class="bg-white rounded-lg p-6 w-full max-w-xl max-h-[90vh] overflow-y-auto">

    <!-- Cerrar -->
    <button class="absolute top-2 right-3 text-xl"
            @click="resetModal">×</button>

    <!-- Paso 1: ingresar CI -->
    <div v-if="!resultado">
      <h3 class="text-lg font-bold mb-3">Ingrese su número de CI</h3>
      <input v-model="ci"
             type="number"
             class="border rounded w-full p-2 mb-3"/>
      <button :disabled="loading || !ci"
              @click="buscar"
              class="card-button w-full btn btn-primary">
        Buscar
      </button>
      <p v-if="error" class="text-red-600 mt-2">{{ error }}</p>
    </div>

    <!-- Paso 2: mostrar resultado -->
    <div v-else>
      <h3 class="font-bold mb-2">
        Resultados para {{ resultado.est.nombre }} {{ resultado.est.apellido }}
      </h3>

      <table class="table table-bordered w-full text-sm">
        <thead><tr>
          <th>Curso</th><th>Monto</th><th>Estado</th><th>Fecha</th><th>Acciones</th>
        </tr></thead>
        <tbody>
          <tr v-for="ins in resultado.inscripciones" :key="ins.id">
            <td>{{ ins.curso }}</td>
            <td>{{ ins.monto }}</td>
            <td>{{ ins.estado }}</td>
            <td>{{ ins.fecha }}</td>
            <td class="space-x-1">

              <!-- Formulario -->
              <a :href="route('public.inscripciones.formulario', ins.id)"
                 target="_blank"
                 class="btn btn-outline-primary btn-sm">
                Formulario
              </a>

              <!-- Certificado (solo si aprobado, id = 3) -->
              <a v-if="ins.estado_id === 3"
                 :href="route('public.inscripciones.certificado', ins.id)"
                 target="_blank"
                 class="btn btn-outline-success btn-sm">
                Certificado
              </a>
            </td>
          </tr>
        </tbody>
      </table>

      <button class="btn btn-secondary mt-4" @click="resetModal">
        Cerrar
      </button>
    </div>

  </div>
</div>

  </GuestLayout> 
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { route } from 'ziggy-js'
import axios from 'axios'
import GuestLayout from '@/Layouts/GuestLayout.vue'

const cursos = ref([])
const showModal   = ref(false)
const ci          = ref('')
const loading     = ref(false)
const error       = ref('')
const resultado   = ref(null)

const cargarCursos = async () => {
  const res = await axios.get('/api/get-cursos')
  cursos.value = res.data
}

onMounted(cargarCursos)

/* ------- búsqueda ---------- */
const buscar = async () => {
  loading.value = true
  error.value   = ''
  resultado.value = null
  try {
    const { data } = await axios.get(
      route('public.inscripciones.buscar', { ci: ci.value })
    )
    resultado.value = data
  } catch (e) {
    error.value = e.response?.data?.msg || 'Error al buscar'
  } finally {
    loading.value = false
  }
}

const resetModal = () => {
  showModal.value = false
  ci.value = ''
  error.value = ''
  resultado.value = null
}
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
