<script setup>
import BackToDashboard from '@/Components/BackToDashboard.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  query: String,
  pacientes: Array,
  medicos: Array,
  productos: Array,
  servicios: Array,
  citas: Array,
  promociones: Array,
  especialidades: Array,
  salas: Array,
  usuarios: Array,
})
</script>

<template>
  <AuthenticatedLayout>
  <BackToDashboard />
  <div class="max-w-4xl mx-auto mt-8">
    <h1 class="text-2xl font-bold mb-4">Resultados de búsqueda para: "{{ query }}"</h1>

    <!-- Pacientes -->
    <div v-if="pacientes && pacientes.length">
      <h2 class="text-lg font-semibold text-blue-700">Pacientes:</h2>
      <ul class="mb-6">
        <li v-for="p in pacientes" :key="p.id">
          {{ p.usuario.nombres }} {{ p.usuario.apellido_paterno }} (CI: {{ p.usuario.ci }})
        </li>
      </ul>
    </div>

    <!-- Médicos -->
    <div v-if="medicos && medicos.length">
      <h2 class="text-lg font-semibold text-blue-700">Médicos:</h2>
      <ul class="mb-6">
        <li v-for="m in medicos" :key="m.id">
          {{ m.usuario.nombres }} {{ m.usuario.apellido_paterno }} (CI: {{ m.usuario.ci }})
        </li>
      </ul>
    </div>

    <!-- Productos -->
    <div v-if="productos && productos.length">
      <h2 class="text-lg font-semibold text-blue-700">Productos:</h2>
      <ul class="mb-6">
        <li v-for="p in productos" :key="p.id">
          {{ p.nombre }} ({{ p.descripcion }}) - {{ p.precio ? '$' + p.precio : '' }}
        </li>
      </ul>
    </div>

    <!-- Servicios -->
    <div v-if="servicios && servicios.length">
      <h2 class="text-lg font-semibold text-blue-700">Servicios:</h2>
      <ul class="mb-6">
        <li v-for="s in servicios" :key="s.id">
          {{ s.nombre }} ({{ s.descripcion }}) - {{ s.precio ? '$' + s.precio : '' }}
        </li>
      </ul>
    </div>

    <!-- Citas -->
    <div v-if="citas && citas.length">
      <h2 class="text-lg font-semibold text-blue-700">Citas:</h2>
      <ul class="mb-6">
        <li v-for="c in citas" :key="c.id">
          {{ c.fecha }} - 
          Paciente: {{ c.paciente?.usuario?.nombres || '-' }} 
          / Médico: {{ c.medico?.usuario?.nombres || '-' }} 
          / Servicio: {{ c.servicio?.nombre || '-' }}
        </li>
      </ul>
    </div>

    <!-- Promociones -->
    <div v-if="promociones && promociones.length">
      <h2 class="text-lg font-semibold text-blue-700">Promociones:</h2>
      <ul class="mb-6">
        <li v-for="p in promociones" :key="p.id">
          {{ p.titulo }} - {{ p.descripcion }}
        </li>
      </ul>
    </div>

    <!-- Especialidades -->
    <div v-if="especialidades && especialidades.length">
      <h2 class="text-lg font-semibold text-blue-700">Especialidades:</h2>
      <ul class="mb-6">
        <li v-for="e in especialidades" :key="e.id">
          {{ e.nombre }}
        </li>
      </ul>
    </div>

    <!-- Salas -->
    <div v-if="salas && salas.length">
      <h2 class="text-lg font-semibold text-blue-700">Salas:</h2>
      <ul class="mb-6">
        <li v-for="s in salas" :key="s.id">
          {{ s.nombre }} ({{ s.descripcion }})
        </li>
      </ul>
    </div>

    <!-- Usuarios -->
    <div v-if="usuarios && usuarios.length">
      <h2 class="text-lg font-semibold text-blue-700">Usuarios:</h2>
      <ul class="mb-6">
        <li v-for="u in usuarios" :key="u.id">
          {{ u.nombres }} {{ u.apellido_paterno }} (CI: {{ u.ci }}) - Rol: {{ u.tipo_usuario }}
        </li>
      </ul>
    </div>

    <!-- Si no hay resultados en nada -->
    <div
      v-if="(!pacientes || pacientes.length === 0) &&
            (!medicos || medicos.length === 0) &&
            (!productos || productos.length === 0) &&
            (!servicios || servicios.length === 0) &&
            (!citas || citas.length === 0) &&
            (!promociones || promociones.length === 0) &&
            (!especialidades || especialidades.length === 0) &&
            (!salas || salas.length === 0) &&
            (!usuarios || usuarios.length === 0)">
      <p class="text-gray-500">No se encontraron resultados.</p>
    </div>
  </div>
  <footer
      class="mt-auto text-center py-2 bg-gray-100 text-gray-700 dark:bg-[#232323] dark:text-gray-300 border-t border-gray-200 dark:border-gray-800"
    >
      <span class="text-sm">
        Visitas a esta página: <b>{{ $page.props.contador_visitas }}</b>
      </span>
  </footer>
  </AuthenticatedLayout>
</template>


