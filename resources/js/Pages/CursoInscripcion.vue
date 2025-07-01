<style>
/* Encender el tema “niño” */

/* Tema “adulto”: fondo gris claro */
.app-2.adulto .info-header {
  background-color: #5E5E5EFF  ;
  color: white;
  padding: 40px;
  border: solid;
  font-weight: bold;
}

/* Tema “niño”: fondo azul muy suave */
.app-2.nino .info-header {
  background-color: #1A3C53FF  ;
  color: white;
  padding: 40px;
  border: solid;
}


</style>

<template>
    <GuestLayout>
        <div class="info-header" style="width: 40%;place-self: center;">
            <div class="grid place-items-center p-4 rounded mb-4">
                        <h1 class="text-2xl font-bold mb-4">Inscribirse al Curso</h1>
                        <p class="mb-1">📘 <strong>Curso:</strong> {{ curso.nombre }}</p>
                        <p class="mb-1">
                            👨‍🏫 <strong>Docente:</strong> {{ curso.profesor?.nombre ?? "—" }}
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
                        <form
                            @submit.prevent="cargarInformacion"
                            class=""
                        >
                            <!-- Campo CI --------------------------------------------------- -->
                            <div class="mb-3">
                                <label class="form-label fw-bold"
                                    >CI *</label
                                >
                                <input
                                    v-model="form.ci"
                                    type="text"
                                    class="form-control"
                                    :class="{ 'is-invalid': form.errors.ci }"
                                    placeholder="Ej. 12345678"
                                />
                                <small
                                    v-if="form.errors.ci"
                                    class="text-danger"
                                >
                                    {{ form.errors.ci }}
                                </small>
                            </div>

                            <!-- Botón para cargar info ------------------------------------ -->
                            <div class="text-end">
                                <button type="submit" class="bt-primary">
                                    <i class="bi bi-search"></i>
                                    Cargar información
                                    <span
                                        v-if="form.processing"
                                        class="spinner-border spinner-border-sm"
                                    ></span>
                                </button>
                            </div>

                            <!-- Muestra datos cargados, si existen -->
                            <div v-if="estudiante.nombre" class="mt-4" style="justify-items: center;">
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
                                    <strong>🎓 Tipo de Estudiante:</strong> {{ estudiante.tipo_estudiante_nombre }}
                                </p>
                                <p v-if="precioDescuento !== null">
                                    <strong>💳 Descuento por tipo de estudiante:</strong> Bs. {{ precioDescuento }}
                                </p>
                            </div>
                            <div v-if="estudiante.nombre" class="text-end mt-3">
                            </div>

                            <div class="text-end mt-4" v-if="estudiante.codigo">
                                <button @click="inscribir" type="button" class="bt-primary">
                                    <i class="bi bi-check-circle"></i>
                                    Inscribirse
                                    <span v-if="inscribiendo" class="spinner-border spinner-border-sm"></span>
                                </button>
                            </div>


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { reactive, ref } from "vue";
import axios from "axios";
import { computed } from "vue";
const { curso } = defineProps({ curso: Object });
const form = reactive({
    ci: "",
    errors: {},
    processing: false,
});

const estudiante = ref({
  nombre: "",
  apellido: "",
  ci: "",
  tipo_estudiante_id: null,
  tipo_estudiante_nombre: "",
});


const cargarInformacion = async () => {
    form.processing = true;
    form.errors = {};

    try {
        const res = await axios.get(`/api/estudiante/${form.ci}`);
        estudiante.value = res.data;
    } catch (err) {
        if (err.response?.status === 404) {
            form.errors.ci = "No se encontró estudiante con ese CI.";
        } else {
            form.errors.ci = "Ocurrió un error al buscar.";
        }
        estudiante.value = {};
    } finally {
        form.processing = false;
    }
};

const precioDescuento = computed(() => {
    if (!estudiante.value.tipo_estudiante_id || !curso.precios) return null;

    const precio = curso.precios.find(
        (p) => p.tipo_estudiante_id === estudiante.value.tipo_estudiante_id
    );

    return precio ? precio.precio : null;
});
const inscribiendo = ref(false);
const inscribir = async () => {
  inscribiendo.value = true;

  try {
    await axios.post('/api/inscripcion', {
      estudiante_id: estudiante.value.codigo,
      curso_id: curso.codigo,
      monto: precioDescuento.value,
    });

    alert('✅ Inscripción realizada correctamente');
    // Puedes redirigir o limpiar el formulario si deseas
  } catch (error) {
    if (error.response?.status === 409) {
      alert('⚠️ Ya estás inscrito en este curso.');
    } else {
      alert('❌ Error al registrar la inscripción.');
    }
  } finally {
    inscribiendo.value = false;
  }
};

</script>
