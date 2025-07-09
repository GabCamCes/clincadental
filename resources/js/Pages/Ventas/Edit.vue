<script setup>
import { ref, computed } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import BackToDashboard from '@/Components/BackToDashboard.vue'

const props = defineProps({
  venta: Object,
  pacientes: Array,
  productos: Array,
  usuarios: Array
})

const form = useForm({
  usuario_id: props.venta.usuario_id,
  paciente_id: props.venta.paciente_id,
  fecha_venta: props.venta.fecha_venta,
  metodo_pago: props.venta.metodo_pago,
  productos: props.venta.productos.map(item => ({
    producto_id: item.producto_id,
    cantidad: item.cantidad
  }))
})

const total = computed(() => {
  return form.productos.reduce((sum, p) => {
    const producto = props.productos.find(prod => prod.id == p.producto_id)
    return sum + (producto ? producto.precio * p.cantidad : 0)
  }, 0)
})

function agregarProducto() {
  form.productos.push({ producto_id: '', cantidad: 1 })
}
function quitarProducto(index) {
  form.productos.splice(index, 1)
}
function submit() {
  form.put(route('ventas.update', props.venta.id))
}
</script>

<template>
  <BackToDashboard />
  <div class="max-w-2xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Editar Venta</h1>
    <form @submit.prevent="submit">

      <div class="mb-4">
        <label class="block mb-1">Vendedor:</label>
        <select v-model="form.usuario_id" class="w-full border rounded p-2">
          <option value="">Selecciona un usuario</option>
          <option v-for="u in usuarios" :key="u.id" :value="u.id">
            {{ u.nombres }} {{ u.apellido_paterno }}
          </option>
        </select>
        <span v-if="form.errors.usuario_id" class="text-red-500 text-sm">{{ form.errors.usuario_id }}</span>
      </div>

      <div class="mb-4">
        <label class="block mb-1">Paciente:</label>
        <select v-model="form.paciente_id" class="w-full border rounded p-2">
          <option value="">Selecciona un paciente</option>
          <option v-for="p in pacientes" :key="p.id" :value="p.id">
            {{ p.usuario?.nombres }} {{ p.usuario?.apellido_paterno }}
          </option>
        </select>
        <span v-if="form.errors.paciente_id" class="text-red-500 text-sm">{{ form.errors.paciente_id }}</span>
      </div>

      <div class="mb-4">
        <label class="block mb-1">Fecha de venta:</label>
        <input type="date" v-model="form.fecha_venta" class="w-full border rounded p-2"/>
        <span v-if="form.errors.fecha_venta" class="text-red-500 text-sm">{{ form.errors.fecha_venta }}</span>
      </div>

      <div class="mb-4">
        <label class="block mb-1">Método de pago:</label>
        <select v-model="form.metodo_pago" class="w-full border rounded p-2">
          <option value="">Selecciona método de pago</option>
          <option value="Efectivo">Efectivo</option>
          <option value="Transferencia">Transferencia</option>
        </select>
        <span v-if="form.errors.metodo_pago" class="text-red-500 text-sm">{{ form.errors.metodo_pago }}</span>
      </div>

      <div class="mb-4 border rounded p-3">
        <div class="flex justify-between items-center mb-2">
          <span class="font-semibold">Productos</span>
          <button type="button" @click="agregarProducto" class="bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-sm">Agregar</button>
        </div>
        <div v-for="(item, idx) in form.productos" :key="idx" class="flex gap-2 items-center mb-2">
          <select v-model="item.producto_id" class="border rounded p-1 w-2/5">
            <option value="">Producto</option>
            <option v-for="p in productos" :value="p.id" :key="p.id">{{ p.nombre }} (Bs {{ p.precio }})</option>
          </select>
          <input type="number" min="1" v-model="item.cantidad" class="border rounded p-1 w-1/5" />
          <button type="button" @click="quitarProducto(idx)" v-if="form.productos.length > 1" class="text-red-500 text-xs ml-2">Quitar</button>
        </div>
        <span v-if="form.errors['productos']" class="text-red-500 text-sm">{{ form.errors['productos'] }}</span>
      </div>

      <div class="mb-4 text-right">
        <b>Total: Bs {{ total.toFixed(2) }}</b>
      </div>

      <div class="flex gap-2">
        <button type="submit" :disabled="form.processing" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Actualizar</button>
        <Link :href="route('ventas.index')" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">Volver</Link>
      </div>
    </form>
  </div>
  <footer
        class="mt-auto text-center py-2 bg-gray-100 text-gray-700 dark:bg-[#232323] dark:text-gray-300 border-t border-gray-200 dark:border-gray-800"
        >
        <span class="text-sm">
            Visitas a esta página: <b>{{ $page.props.contador_visitas }}</b>
        </span>
  </footer>
</template>
