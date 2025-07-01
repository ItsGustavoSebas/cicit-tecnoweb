<template>
  <AuthenticatedLayout>
    <Head title="Dashboard" />

    <div class="container my-4">
      <div class="row g-4">

        <!-- Total Ingresos -->
        <div class="col-12">
          <div class="card p-3">
            <h4>Total Ingresos</h4>
            <p class="display-6">{{ formatCurrency(totalIngresos) }}</p>
          </div>
        </div>

        <!-- Gráfico de Barras: Rutas más accedidas -->
        <div class="col-md-6">
          <div class="card p-3">
            <h5>Rutas más accedidas</h5>
            <canvas ref="rutasChart"></canvas>
          </div>
        </div>

        <!-- Gráfico de Pastel: Cursos más solicitados -->
        <div class="col-md-6">
          <div class="card pt-3 pl-3 pr-3 pb-5" style="max-height: 350px;">
            <h5>Cursos más solicitados</h5>
            <canvas ref="cursosChart"></canvas>
          </div>
        </div>

        <!-- Listas adicionales -->
        <div class="col-md-4">
          <div class="card p-3 h-100">
            <h5>Top Estudiantes (inscripciones)</h5>
            <ul class="list-group list-group-flush">
              <li
                v-for="e in estudiantesTop"
                :key="e.estudiante_id"
                class="list-group-item d-flex justify-content-between"
              >
                {{ e.nombre }}
                <span class="badge bg-primary">{{ e.total }}</span>
              </li>
            </ul>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card p-3 h-100">
            <h5>Top Profesores (cursos)</h5>
            <ul class="list-group list-group-flush">
              <li
                v-for="p in profesoresTop"
                :key="p.profesor_id"
                class="list-group-item d-flex justify-content-between"
              >
                {{ p.nombre }}
                <span class="badge bg-success">{{ p.total }}</span>
              </li>
            </ul>
          </div>
        </div>

      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import Chart from 'chart.js/auto'
import { ref, onMounted } from 'vue'
import { Head, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'


const { visitasTotal, cursosSolicitados, estudiantesTop, profesoresTop, totalIngresos } = usePage().props

// Refs a los canvas
const rutasChart = ref(null)
const cursosChart = ref(null)

onMounted(() => {
  // Datos y etiquetas para rutas
  const rutasLabels = visitasTotal.map(v => v.ruta)
  const rutasData   = visitasTotal.map(v => v.total)

  new Chart(rutasChart.value, {
    type: 'bar',
    data: {
      labels: rutasLabels,
      datasets: [{
        label: 'Accesos',
        data: rutasData,
        backgroundColor: 'rgba(54, 162, 235, 0.5)',
        borderColor:   'rgba(54, 162, 235, 1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } }
    }
  })

  // Datos y etiquetas para cursos
  const cursosLabels = cursosSolicitados.map(c => c.nombre)
  const cursosData   = cursosSolicitados.map(c => c.total)
  const colores = [
    '#FF6384','#36A2EB','#FFCE56','#4BC0C0','#9966FF'
  ]

  new Chart(cursosChart.value, {
    type: 'pie',
    data: {
      labels: cursosLabels,
      datasets: [{
        data: cursosData,
        backgroundColor: colores.slice(0, cursosData.length),
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { position: 'bottom' } }
    }
  })
})

// Formateo de moneda
function formatCurrency(value) {
  return new Intl.NumberFormat('es-BO', {
    style: 'currency',
    currency: 'BOB',
    minimumFractionDigits: 2
  }).format(value)
}
</script>

<style scoped>
.card {
  border: 1px solid #ddd;
  border-radius: 0.25rem;
}
</style>
