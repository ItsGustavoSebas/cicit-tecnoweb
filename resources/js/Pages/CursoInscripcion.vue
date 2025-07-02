<style>
/* Encender el tema “niño” */

/* Tema “adulto”: fondo gris claro */
.app-2.adulto .info-header {
    background-color: #5e5e5eff;
    color: white;
    padding: 40px;
    border: solid;
    font-weight: bold;
}

/* Tema “niño”: fondo azul muy suave */
.app-2.nino .info-header {
    background-color: #1a3c53ff;
    color: white;
    padding: 40px;
    border: solid;
}
</style>

<template>
    <GuestLayout>
        <div class="info-header" style="width: 40%; place-self: center">
            <div class="grid place-items-center p-4 rounded mb-4">
                <h1 class="text-2xl font-bold mb-4">Inscribirse al Curso</h1>
                <p class="mb-1">
                    📘 <strong>Curso:</strong> {{ curso.nombre }}
                </p>
                <p class="mb-1">
                    👨‍🏫 <strong>Docente:</strong>
                    {{ curso.profesor?.nombre ?? "—" }}
                </p>
                <p class="mb-1">
                    📅 <strong>Duración:</strong> {{ curso.duracion }}
                </p>
                <p class="mb-3">
                    💰 <strong>Precio:</strong> Bs.
                    {{ curso.precios?.[0]?.precio ?? "—" }}
                </p>

                <div v-if="curso.cronogramas?.length" class="mb-3">
                    <p class="font-semibold mb-1">🗓️ Cronograma:</p>
                    <ul class="list-disc pl-5 text-sm">
                        <li v-for="(c, i) in curso.cronogramas" :key="i">
                            {{ c.dia }} — {{ c.hora_inicio }} a {{ c.hora_fin }}
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-12">
                <!-- Tarjeta Bootstrap -->
                <div class="">
                    <div class="card-header">
                        <h5 class="mb-0">Rellenar Información:</h5>
                    </div>

                    <div class="card-body">
                        <form @submit.prevent="cargarInformacion" class="">
                            <!-- Campo CI --------------------------------------------------- -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">CI *</label>
                                <input v-model="form.ci" type="text" class="form-control"
                                    :class="{ 'is-invalid': form.errors.ci }" placeholder="Ej. 12345678" />
                                <small v-if="form.errors.ci" class="text-danger">
                                    {{ form.errors.ci }}
                                </small>
                            </div>

                            <!-- Botón para cargar info ------------------------------------ -->
                            <div class="text-end">
                                <button type="submit" class="bt-primary">
                                    <i class="bi bi-search"></i>
                                    Cargar información
                                    <span v-if="form.processing" class="spinner-border spinner-border-sm"></span>
                                </button>
                            </div>

                            <!-- Muestra datos cargados, si existen -->
                            <div v-if="estudiante.nombre" class="mt-4" style="justify-items: center">
                                <h6 class="fw-bold">Datos del estudiante</h6>
                                <p>
                                    <strong>Nombre:</strong>
                                    {{ estudiante.nombre }}
                                </p>
                                <p>
                                    <strong>Apellido:</strong>
                                    {{ estudiante.apellido }}
                                </p>
                                <p><strong>CI:</strong> {{ estudiante.ci }}</p>

                                <p v-if="estudiante.tipo_estudiante_nombre">
                                    <strong>🎓 Tipo de Estudiante:</strong>
                                    {{ estudiante.tipo_estudiante_nombre }}
                                </p>
                                <p v-if="precioPagar ?? precioDescuento">
                                    <strong>💳 Monto a pagar:</strong>
                                    Bs. {{ precioPagar ?? precioDescuento }}
                                </p>
                                <div class="text-end mt-4" v-if="estudiante.codigo">
                                    <button @click="inscribir" type="button" class="bt-primary" :disabled="inscribiendo">
                                        <i class="bi bi-check-circle"></i> Inscribirse
                                        <span v-if="inscribiendo" class="spinner-border spinner-border-sm"></span>
                                    </button>
                                </div>
                            </div>
                            <div v-if="estudiante.nombre" class="text-end mt-3"></div>
                            <div v-else-if="showNewForm" class="mt-4 border-top pt-4">
                                <h6 class="fw-bold mb-3">Nuevo estudiante</h6>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nombre *</label>
                                        <input v-model="newEst.nombre" type="text" class="form-control"
                                            :class="{ 'is-invalid': estErr.nombre }" />
                                        <div class="invalid-feedback">{{ estErr.nombre }}</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Apellido *</label>
                                        <input v-model="newEst.apellido" type="text" class="form-control"
                                            :class="{ 'is-invalid': estErr.apellido }" />
                                        <div class="invalid-feedback">{{ estErr.apellido }}</div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">CI *</label>
                                        <input v-model="newEst.ci" type="text" class="form-control"
                                            :class="{ 'is-invalid': estErr.ci }" />
                                        <div class="invalid-feedback">{{ estErr.ci }}</div>
                                    </div>

                                  
                                </div>

                                <div class="text-end mt-3">
                                    <button @click="crearEstudiante" type="button" class="bt-primary" :disabled="creando">
                                        <i class="bi bi-save"></i> Guardar estudiante
                                        <span v-if="creando" class="spinner-border spinner-border-sm"></span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import { reactive, ref, computed } from 'vue'
import axios from 'axios'
import { createVNode } from 'vue';

const { curso, tipos } = defineProps({ curso: Object, tipos: Array })

/* ---------- 1) Búsqueda por CI ---------- */
const form = reactive({ ci: '', errors: {}, processing: false })
const estudiante = ref({})          // objeto lleno si lo encuentra
const showNewForm = ref(false)      // ← NUEVO

/* ---------- 2) Formulario Nuevo Est. ---------- */
const creando= ref(false)
const newEst = reactive({ nombre: '', apellido: '', ci: '', tipo_estudiante_id: '' })  // NUEVO
const estErr = reactive({})                                                        // NUEVO

/* ---------- 3) Inscripción ---------- */
const inscribiendo = ref(false)
const precioDescuento = computed(() => {
    if (!estudiante.value.tipo_estudiante_id || !curso.precios) return null
    const p = curso.precios.find(x => x.tipo_estudiante_id === estudiante.value.tipo_estudiante_id)
    return p ? p.precio : null
})
const precioPagar = ref(null);
/* ---------------- MÉTODOS ---------------- */
async function cargarInformacion() {
    form.processing = true
    form.errors = {}
    showNewForm.value = false
    try {
        const { data } = await axios.get(`/api/estudiante/${form.ci}`)
        estudiante.value = data                     // encontrado
    } catch (err) {
        if (err.response?.status === 404) {         // no existe → mostrar nuevo form
            newEst.ci = form.ci
            showNewForm.value = true
        } else {
            form.errors.ci = 'Error al buscar'
        }
        estudiante.value = {}
    } finally { form.processing = false }
}

async function crearEstudiante() {              // NUEVO
    creando.value=true
    Object.keys(estErr).forEach(k => estErr[k] = '')
    try {
        const { data } = await axios.post('/api/estudianteNuevo', {
            ...newEst,
            curso_id: curso.codigo       
        })
        estudiante.value  = data.estudiante  
        precioPagar.value = data.precio   
        showNewForm.value = false
    } catch (err) {
        if (err.response?.status === 422) Object.assign(estErr, err.response.data.errors)
        else alert('❌ Error al crear estudiante')
    } finally { creando.value=false }
}

async function inscribir() {
    inscribiendo.value = true
    try {
        await axios.post('/api/inscripcion', {
            estudiante_id: estudiante.value.codigo,
            curso_id: curso.codigo,
            monto: precioPagar.value ?? precioDescuento.value,
        })
        alert('✅ Inscripción realizada correctamente')
    } catch (e) {
        alert(e.response?.data.error || '❌ Error al inscribir')
    } finally { inscribiendo.value = false }
}
</script>
